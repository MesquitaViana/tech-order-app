<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;

class AccountController extends Controller
{
    /**
     * Tela de login (e-mail + CPF)
     */
    public function showLoginForm(Request $request)
    {
        // Já logado → manda para a lista de pedidos
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
        $cpf   = preg_replace('/\D/', '', $data['cpf']); // remove . e -
        $cpfHash = hash('sha256', $cpf);

        // Procura cliente
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

        // Salva sessão
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
     * Lista de pedidos do cliente autenticado
     * + carrega assinaturas para o bloco "Minhas assinaturas"
     */
    public function orders(Request $request)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        // Carrega cliente já com relacionamento de assinaturas
        $customer = Customer::with('subscriptions')
            ->findOrFail($customerId);

        // Pedidos do cliente (pode usar paginate se preferir)
        $orders = Order::where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        // Assinaturas do cliente (se já existirem no banco)
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
     * Mostra o detalhe de um pedido pertencente ao cliente
     */
    public function showOrder(Request $request, $id)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::findOrFail($customerId);

        // Só busca o pedido SE pertencer ao cliente
        $order = Order::with([
                'items',
                'statusEvents' => function ($q) {
                    $q->orderByDesc('created_at');
                }
            ])
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        return view('customer.orders.show', [
            'customer' => $customer,
            'order'    => $order,
        ]);
    }

    /**
     * Marca termos de entrega (rastreamento) como aceitos para um pedido
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

        // Só faz sentido se tiver código de rastreio
        if (!$order->tracking_code) {
            return redirect()
                ->route('customer.orders.show', $order->id)
                ->with('status_error', 'Este pedido ainda não possui código de rastreio.');
        }

        // Se já aceitou, só redireciona
        if ($order->tracking_terms_accepted_at) {
            return redirect()
                ->route('customer.orders.show', $order->id);
        }

        $order->tracking_terms_accepted_at = now();
        $order->tracking_terms_accepted_ip = $request->ip();
        $order->tracking_terms_version     = '1.0'; // depois dá pra puxar de config
        $order->save();

        return redirect()
            ->route('customer.orders.show', $order->id)
            ->with('status_message', 'Termos de entrega aceitos com sucesso.');
    }

    /**
     * (ESBOÇO) Tela inicial do assistente de assinaturas com IA
     * GET /minha-conta/assinaturas/assistente
     */
    public function showSubscriptionAssistant(Request $request)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::with(['orders.items'])
            ->findOrFail($customerId);

        // Últimos pedidos para exibir/usar no assistente
        $recentOrders = $customer->orders()
            ->with('items')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('customer.subscriptions.assistant', [
            'customer'     => $customer,
            'recentOrders' => $recentOrders,
        ]);
    }

    /**
     * (ESBOÇO) Processa respostas do questionário e gera recomendações
     * POST /minha-conta/assinaturas/assistente
     *
     * Aqui depois vamos plugar a chamada de IA (API externa).
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
        'budget'           => ['nullable', 'string', 'max:255'],
    ]);

    // 1) Histórico de pedidos do cliente (últimos 10)
    $orders = Order::with('items')
        ->where('customer_id', $customer->id)
        ->orderByDesc('created_at')
        ->limit(10)
        ->get();

    $orderHistoryText = $orders->map(function ($order) {
        $items = $order->items->map(function ($item) {
            return "{$item->name} (qty: {$item->quantity})";
        })->implode(', ');

        return "Pedido #{$order->id} ({$order->created_at->format('d/m/Y')}): {$items} - total R$ {$order->total}";
    })->implode("\n");

    // 2) Preferências e orçamento informados agora
    $userText = $data['preferences_text'];
    $budget   = $data['budget'] ?: 'não informado';

    // 3) Prompt para a IA
    $systemPrompt = <<<PROMPT
Você é um assistente especialista em pods descartáveis, refis e dispositivos de vaporização.

Tarefas principais:
- Ler o histórico de pedidos do cliente.
- Ler o texto que ele escreveu dizendo o que usa hoje, o que gosta e o que procura.
- Ler o orçamento mensal aproximado.
- Sugerir de 1 a 3 "Boxes de assinatura" mensais, sempre em português do Brasil.

Importante:
- Você NÃO conhece o estoque em tempo real, então fale em termos de tipos de produto e faixas de preço (ex.: "2 pods descartáveis de 30k puffs faixa R\$ 90–120 cada").
- Foque em perfil de uso (frequência, preferência de sabor, tipo de produto).
- Use um tom simples, claro e amigável.
- Sempre explique em poucas linhas POR QUE aquela Box faz sentido para o cliente.

Formato da resposta:
- Um pequeno parágrafo de resumo.
- Depois liste as boxes como:
  1) Nome da Box
     - Perfil de uso recomendado
     - Conteúdo aproximado (tipos de produto)
     - Faixa de valor estimada por mês
PROMPT;

    // 4) Chamada à IA (exemplo usando OpenAI PHP; adapte se estiver usando outro client)
    $client = \OpenAI::client(config('services.openai.key')); // ou env('OPENAI_API_KEY')

    $response = $client->chat()->create([
        'model'    => 'gpt-4.1-mini', // ou outro modelo que você escolher
        'messages' => [
            [
                'role'    => 'system',
                'content' => $systemPrompt,
            ],
            [
                'role'    => 'user',
                'content' =>
                    "Histórico de pedidos do cliente:\n" .
                    $orderHistoryText .
                    "\n\nTexto que o cliente escreveu:\n" .
                    $userText .
                    "\n\nOrçamento mensal aproximado: " .
                    $budget,
            ],
        ],
    ]);

    $recommendation = $response->choices[0]->message->content
        ?? 'Não foi possível gerar uma recomendação agora.';

    return view('customer.subscriptions.assistant_result', [
        'customer'         => $customer,
        'orders'           => $orders,
        'preferences_text' => $userText,
        'budget'           => $budget,
        'recommendation'   => $recommendation,
    ]);
}


}