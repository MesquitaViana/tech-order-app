<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;

class AccountController extends Controller
{
    // Tela de login (e-mail + CPF)
    public function showLoginForm(Request $request)
    {
        // Se já estiver logado, manda direto para os pedidos
        if ($request->session()->has('customer_id')) {
            return redirect()->route('customer.orders');
        }

        return view('customer.login');
    }

    // Processa login
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'cpf'   => ['required', 'string', 'min:11', 'max:14'],
        ]);

        $email = $data['email'];
        $cpf   = preg_replace('/\D/', '', $data['cpf']); // tira pontos e traços
        $cpfHash = hash('sha256', $cpf);

        $customer = Customer::where('email', $email)
            ->where('cpf_hash', $cpfHash)
            ->first();

        if (!$customer) {
            return back()
                ->withInput()
                ->withErrors(['login' => 'E-mail ou CPF não encontrados. Verifique os dados e tente novamente.']);
        }

        // Autentica via sessão simples
        $request->session()->put('customer_id', $customer->id);

        return redirect()->route('customer.orders');
    }

    // Logout
    public function logout(Request $request)
    {
        $request->session()->forget('customer_id');

        return redirect()->route('customer.login');
    }

    // Lista de pedidos do cliente autenticado
    public function orders(Request $request)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::findOrFail($customerId);

        $orders = Order::where('customer_id', $customer->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.orders.index', compact('customer', 'orders'));
    }

    // Detalhe de um pedido do cliente (garante que o pedido pertence a ele)
    public function showOrder(Request $request, $id)
    {
        $customerId = $request->session()->get('customer_id');

        if (!$customerId) {
            return redirect()->route('customer.login');
        }

        $customer = Customer::findOrFail($customerId);

        $order = Order::with(['items', 'statusEvents' => function ($q) {
            $q->orderByDesc('created_at');
        }])
            ->where('customer_id', $customer->id)
            ->findOrFail($id);

        return view('customer.orders.show', compact('customer', 'order'));
    }
}
