<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusEvent;
use App\Models\FailedWebhook;
use App\Services\LunaStatusMapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Throwable;

class LunaWebhookController extends Controller
{
    public function handle(Request $request)
    {
        try {
            /*
             |--------------------------------------------------------------------------
             | 1) Validação do token
             |--------------------------------------------------------------------------
             */
            $token = (string) $request->query('token');
            if ($token !== (string) config('services.luna.webhook_token')) {
                return response()->json(['ok' => false, 'message' => 'unauthorized'], 401);
            }

            $payload = $request->all();

            /*
             |--------------------------------------------------------------------------
             | Validação do webhook no painel da Luna
             |--------------------------------------------------------------------------
             */
            if (($payload['event'] ?? null) === 'test-new-webhook') {
                return response()->json([
                    'ok'      => true,
                    'message' => 'webhook validated',
                ]);
            }

            /*
             |--------------------------------------------------------------------------
             | 2) Evento + external_id (NORMALIZADO – SEM DUPLICAÇÃO)
             |--------------------------------------------------------------------------
             */
            $eventName = (string) ($payload['event'] ?? $payload['type'] ?? '');

            $rawExternalId = (string) ($payload['id'] ?? '');
            if ($rawExternalId === '') {
                return response()->json([
                    'ok'      => false,
                    'message' => 'missing external_id',
                ], 422);
            }

            // remove prefixo c- e espaços
            $externalId = trim($rawExternalId);
            $externalId = preg_replace('/^c-/i', '', $externalId);

            if ($externalId === '') {
                return response()->json([
                    'ok'      => false,
                    'message' => 'missing external_id',
                ], 422);
            }

            /*
             |--------------------------------------------------------------------------
             | 3) Gateway status correto (payment.status)
             |--------------------------------------------------------------------------
             */
            $gatewayStatusFromPayload =
                $payload['payment']['status'] ??
                ($payload['status'] ?? null);

            $mapped = LunaStatusMapper::map($eventName, $gatewayStatusFromPayload);

            $internalStatus = (string) ($mapped['status'] ?? 'pending');
            $gatewayStatus  = (string) ($mapped['gateway_status'] ?? ($gatewayStatusFromPayload ?? $eventName));

            /*
             |--------------------------------------------------------------------------
             | 4) Método de pagamento (se vier)
             |--------------------------------------------------------------------------
             */
            $paymentMethod =
                (string) ($payload['payment_method'] ?? '') ?:
                (string) ($payload['paymentMethod'] ?? '') ?:
                (string) ($payload['payment']['method'] ?? '') ?:
                (string) ($payload['payment']['type'] ?? '');

            /*
             |--------------------------------------------------------------------------
             | 5) Persistência (IDEMPOTENTE)
             |--------------------------------------------------------------------------
             */
            DB::transaction(function () use (
                $payload,
                $externalId,
                $eventName,
                $internalStatus,
                $gatewayStatus,
                $paymentMethod
            ) {
                /*
                 | Pedido (idempotente)
                 */
                $order = Order::firstOrNew(['external_id' => $externalId]);

                // ✅ PASSO 7) Captura status anterior + se é pedido novo (antes de mudar status)
                $oldStatus   = (string) ($order->status ?? '');
                $wasNewOrder = ! $order->exists;

                /*
                 | ===============================
                 | CLIENTE (EXATAMENTE COMO PEDIDO)
                 | ===============================
                 */
                $client = $payload['client']
                    ?? ($payload['sale']['customer'] ?? null)
                    ?? [];

                $email = $client['email'] ?? null;

                if (!$email) {
                    throw new \Exception('Webhook sem client.email');
                }

                // normaliza CPF (remove tudo que não for número)
                $cpf = isset($client['doc'])
                    ? preg_replace('/\D+/', '', (string) $client['doc'])
                    : null;

                $customer = Customer::firstOrNew(['email' => $email]);

                $customer->name  = $client['name'] ?? ($customer->name ?? 'Cliente');
                $customer->phone = $client['phone'] ?? $customer->phone;

                // ✅ ESSENCIAL: cpf_hash para login Minha Conta
                if ($cpf) {
                    $customer->cpf_hash = hash('sha256', $cpf);
                }

                $customer->save();

                $order->customer_id = $customer->id;

                /*
                 | Status do pedido
                 */
                if (Schema::hasColumn('orders', 'status')) {
                    $order->status = $internalStatus;
                }

                if (Schema::hasColumn('orders', 'gateway_status')) {
                    $order->gateway_status = $gatewayStatus;
                }

                if (!empty($paymentMethod) && Schema::hasColumn('orders', 'payment_method')) {
                    $order->payment_method = $paymentMethod;
                }

                /*
                 | Totais / endereço (se vier)
                 */
                if (isset($payload['amount']) && Schema::hasColumn('orders', 'total_amount')) {
                    $order->total_amount = $payload['amount'];
                }

                if (isset($payload['address']) && Schema::hasColumn('orders', 'shipping_address')) {
                    $order->shipping_address = json_encode(
                        $payload['address'],
                        JSON_UNESCAPED_UNICODE
                    );
                }

                $order->save();

                // ✅ PASSO 7) Disparo de e-mails após commit (não envia se transação falhar)
                DB::afterCommit(function () use ($order, $oldStatus, $wasNewOrder, $gatewayStatus) {
                    $order->load('customer');

                    $svc = app(\App\Services\OrderEmailService::class);

                    if ($wasNewOrder) {
                        $svc->pedidoCriado($order);
                        $svc->acessoConta($order);
                    }

                    // gateway_status -> e-mails
                    if ((string) $gatewayStatus === 'pending') {
                        $svc->aguardandoPagamento($order);
                    }

                    if ((string) $gatewayStatus === 'paid') {
                        $svc->pagamentoAprovado($order);
                    }

                    $newStatus = (string) ($order->status ?? '');
                    if ($oldStatus !== '' && $newStatus !== '' && $oldStatus !== $newStatus) {
                        $svc->statusAtualizado($order, $oldStatus, $newStatus);
                    }
                });

                /*
                 | Itens (somente se vierem)
                 */
                if (array_key_exists('items', $payload)) {
                    OrderItem::where('order_id', $order->id)->delete();

                    foreach ($payload['items'] ?? [] as $it) {
                        $qty = (int) ($it['qty'] ?? $it['quantity'] ?? 1);

                        OrderItem::create([
                            'order_id' => $order->id,
                            'name'     => $it['name'] ?? $it['title'] ?? 'Item',
                            'sku'      => $it['sku'] ?? ($it['id'] ?? null),
                            'quantity' => $qty,
                            'price'    => $it['price'] ?? null,
                        ]);
                    }
                }

                /*
                 | Histórico de status (não duplica)
                 */
                $last = OrderStatusEvent::where('order_id', $order->id)
                    ->orderByDesc('created_at')
                    ->first();

                if (!$last || (string) $last->status !== (string) $internalStatus) {
                    OrderStatusEvent::create([
                        'order_id' => $order->id,
                        'status'   => $internalStatus,
                        'source'   => 'luna',
                        'comment'  => "Evento Luna: {$eventName} | gateway_status={$gatewayStatus}",
                    ]);
                }
            });

            return response()->json(['ok' => true]);

        } catch (Throwable $e) {
            FailedWebhook::create([
                'source'  => 'luna',
                'payload' => json_encode($request->all(), JSON_UNESCAPED_UNICODE),
                'error'   => $e->getMessage(),
            ]);

            return response()->json([
                'ok'      => false,
                'message' => 'internal error',
            ], 500);
        }
    }
}
