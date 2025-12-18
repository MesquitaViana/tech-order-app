<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use OpenAI\Exceptions\RateLimitException;

class AccountController extends Controller
{
    /**
     * Tela de login (e-mail + CPF)
     */
    public function showLoginForm(Request $request)
    {
        if ($request->session()->has('customer_id')) {
            return redirect()->route('customer.orders');
        }

        return view('customer.login');
    }

    /**
     * Processa login do cliente
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'cpf'   => ['required', 'string', 'min:11', 'max:14'],
        ]);

        $email = $data['email'];
        $cpf = preg_replace('/\D/', '', $data['cpf']);
        $cpfHash = hash('sha256', $cpf);

        $customer = Customer::where('email', $email)
            ->where('cpf_hash', $cpfHash)
            ->first();

        if (!$customer) {
            return back()
                ->withInput()
                ->withErrors([
                    'login' => 'E-mail ou CPF não encontrados. Verifique os dados e tente novamente.'
                ]);
        }

        $request->session()->put('customer_id', $customer->id);

        return redirect()->route('customer.orders');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        $request->session()->forget('customer_id');
        return redirect()->route('customer.login');
    }

    /**
     * Lista de pedidos + assinaturas
     */
    public function orders(Request $request)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::with('subscriptions')->findOrFail($customerId);

        $orders = Order::where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        $subscriptions = $customer->subscriptions()
            ->orderByDesc('created_at')
            ->get();

        return view('customer.orders.index', [
            'customer'      => $customer,
            'orders'        => $orders,
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Detalhe de pedido
     */
    public function showOrder(Request $request, $id)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::findOrFail($customerId);

        $order = Order::with([
                'items',
                'statusEvents' => fn($q) => $q->orderByDesc('created_at'),
            ])
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        return view('customer.orders.show', [
            'customer' => $customer,
            'order'    => $order,
        ]);
    }

    /**
     * Aceita termos de entrega
     */
    public function acceptTrackingTerms(Request $request, $id)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $order = Order::where('id', $id)
            ->where('customer_id', $customerId)
            ->firstOrFail();

        if (!$order->tracking_code) {
            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('status_error', 'Este pedido ainda não possui código de rastreio.');
        }

        if ($order->tracking_terms_accepted_at) {
            return redirect()->route('customer.orders.show', $order->id);
        }

        $order->tracking_terms_accepted_at = now();
        $order->tracking_terms_accepted_ip = $request->ip();
        $order->tracking_terms_version     = '1.0';
        $order->save();

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('status_message', 'Termos de entrega aceitos com sucesso.');
    }

    /**
     * GET — Tela do assistente de assinaturas
     */
    public function showSubscriptionAssistant(Request $request)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::findOrFail($customerId);

        return view('customer.subscriptions.assistant', [
            'customer' => $customer,
            'aiResult' => null,
            'aiError'  => null,
        ]);
    }

    /**
     * POST — Processa o texto + chamada IA (responses API)
     */
    public function processSubscriptionAssistant(Request $request)
    {
        $customerId = $request->session()->get('customer_id');
        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::findOrFail($customerId);

        $data = $request->validate([
            'preferences_text' => ['required', 'string', 'max:2000'],
            'budget'           => ['nullable', 'string', 'max:50'],
        ]);

        $orders = Order::with('items')
            ->where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        $orderHistoryText = $orders->map(function ($order) {
            $items = $order->items->map(fn($i) => "{$i->product_name} (x{$i->quantity})")
                ->implode(', ');

            return "Pedido #{$order->id} em {$order->created_at->format('d/m/Y')}: {$items} - total R$ {$order->total}";
        })->implode("\n");

        $userText = $data['preferences_text'];
        $budget   = $data['budget'] ?: 'não informado';

        $prompt = <<<PROMPT
Você é um assistente de assinaturas da Tech Market Brasil.

Dados do cliente:
- Nome: {$customer->name}
- E-mail: {$customer->email}

Histórico recente (até 10 pedidos):
{$orderHistoryText}

Descrição do cliente:
"{$userText}"

Orçamento mensal informado: {$budget}

Tarefa:
Proponha de 1 a 3 Boxes de Assinatura com:
- Nome da Box
- Faixa de preço aproximada
- Quantidade estimada
- Perfil de sabor
- Justificativa curta

Formato simples (sem markdown):

1) Nome da Box: ...
   Preço aproximado: ...
   Quantidade: ...
   Perfil de sabor: ...
   Justificativa: ...

2) ...
PROMPT;

        try {
            $client = \OpenAI::client(config('services.openai.key'));

            $response = $client->responses()->create([
                'model' => config('services.openai.model', 'gpt-4.1-mini'),
                'input' => $prompt,
            ]);

            $aiText = trim($response->outputText ?? $response->outputText());

            return view('customer.subscriptions.assistant', [
                'customer' => $customer,
                'aiResult' => $aiText,
                'aiError'  => null,
            ]);

        } catch (RateLimitException $e) {

            Log::warning('OpenAI rate limit: '.$e->getMessage());

            return view('customer.subscriptions.assistant', [
                'customer' => $customer,
                'aiResult' => null,
                'aiError'  => 'Nosso assistente está recebendo muitas solicitações. Tente novamente em alguns minutos.',
            ]);

        } catch (\Throwable $e) {

            Log::error('Erro IA: '.$e->getMessage());

            return view('customer.subscriptions.assistant', [
                'customer' => $customer,
                'aiResult' => null,
                'aiError'  => 'Ocorreu um erro ao gerar a recomendação. Tente novamente mais tarde.',
            ]);
        }
    }
}
