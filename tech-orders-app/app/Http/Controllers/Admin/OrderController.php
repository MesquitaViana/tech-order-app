<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderStatusEvent;


class OrderController extends Controller
{
    // Lista de pedidos com busca básica
    public function index(Request $request)
    {
        $query = Order::with('customer')->orderByDesc('created_at');

        if ($search = $request->get('search')) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })->orWhere('external_id', 'like', "%{$search}%");
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders', 'search'));
    }

    // Detalhe de um pedido
    public function show($id)
    {
        $order = Order::with(['customer', 'items', 'statusEvents' => function ($q) {
            $q->orderByDesc('created_at');
        }])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
{
    $order = Order::findOrFail($id);

    $request->validate([
        'status'  => ['required', 'string', 'max:50'],
        'comment' => ['nullable', 'string', 'max:255'],
    ]);

    $oldStatus = $order->status;
    $newStatus = $request->input('status');

    // Atualiza status interno do pedido
    $order->status = $newStatus;
    $order->save();

    // Registra no histórico
    OrderStatusEvent::create([
        'order_id' => $order->id,
        'status'   => $newStatus,
        'source'   => 'admin',
        'comment'  => $request->input('comment') ?: "Status alterado de '{$oldStatus}' para '{$newStatus}' pelo admin.",
    ]);

    return redirect()
        ->route('admin.orders.show', $order->id)
        ->with('status_message', 'Status atualizado com sucesso!');
}
}
