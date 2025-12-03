<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusEvent;
use Illuminate\Support\Facades\Log;

class LunaWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1) Valida token na query string: ?token=abcd12322
        $token = $request->query('token');

        if ($token !== config('services.luna.webhook_token')) {
            Log::warning('Luna webhook: token inválido', [
                'query_token' => $token,
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = $request->all();

        Log::info('Luna webhook recebido', [
            'payload' => $data,
        ]);

        // 2) Campos principais do payload
        $externalId = $data['id'] ?? null;
        $event      = $data['event'] ?? null;
        $status     = $data['status'] ?? null;
        $amount     = $data['amount'] ?? 0;
        $method     = $data['method'] ?? null;

        $client  = $data['client']  ?? [];
        $items   = $data['items']   ?? [];
        $address = $data['address'] ?? [];

        if (!$externalId || empty($client)) {
            return response()->json(['error' => 'Dados insuficientes'], 422);
        }

        // 3) Cliente (Customer)
        $email = $client['email'] ?? null;
        $name  = $client['name'] ?? 'Cliente';

        $cpf   = $client['doc'] ?? null;
        $cpfHash = null;

        if ($cpf) {
            // simples por enquanto: hash do CPF
            $cpfHash = hash('sha256', $cpf);
        }

        $customer = Customer::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'cpf_hash' => $cpfHash,
                'phone'    => $client['phone'] ?? null,
            ]
        );

        // atualiza se faltava info
        if (!$customer->cpf_hash && $cpfHash) {
            $customer->cpf_hash = $cpfHash;
        }
        if (!$customer->phone && !empty($client['phone'])) {
            $customer->phone = $client['phone'];
        }
        $customer->save();

        // 4) Pedido (Order)
        $order = Order::firstOrNew(['external_id' => $externalId]);

        $order->customer_id    = $customer->id;
        $order->gateway_status = $status;
        $order->amount         = (float) $amount;
        $order->method         = $method;

        // Endereço
        $order->city         = $address['city'] ?? null;
        $order->state        = $address['state'] ?? null;
        $order->zipcode      = $address['zipcode'] ?? null;
        $order->street       = $address['street'] ?? null;
        $order->number       = $address['number'] ?? null;
        $order->complement   = $address['complement'] ?? null;
        $order->neighborhood = $address['neighborhood'] ?? null;

        if (!$order->exists) {
            $order->status = 'novo'; // status interno inicial
        }

        $order->raw_payload = $data;
        $order->save();

        // 5) Itens (recria a lista sempre)
        if (!empty($items)) {
            $order->items()->delete();

            foreach ($items as $item) {
                $order->items()->create([
                    'item_id_gateway' => $item['id'] ?? null,
                    'name'            => $item['name'] ?? 'Item',
                    'price'           => isset($item['price']) ? (float) $item['price'] : 0,
                    'quantity'        => isset($item['quantity']) ? (int) $item['quantity'] : 1,
                    'description'     => $item['description'] ?? null,
                ]);
            }
        }

        // 6) Histórico do evento
        if ($event) {
            OrderStatusEvent::create([
                'order_id' => $order->id,
                'status'   => $event,
                'source'   => 'system',
                'comment'  => 'Evento vindo da Luna: ' . $status,
            ]);
        }

        return response()->json(['ok' => true]);
    }
}
