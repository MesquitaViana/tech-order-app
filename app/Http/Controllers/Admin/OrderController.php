<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Lista de pedidos com busca e filtro de status
     */
    public function index(Request $request)
    {
        $search = trim($request->input('q', ''));
        $status = $request->input('status', '');

        $query = Order::with('customer')
            ->orderByDesc('id');

        // Filtro por status interno
        if ($status !== '') {
            $query->where('status', $status);
        }

        // Busca geral: ID interno, ID externo, nome, email
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                  ->orWhere('external_id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Detalhe do pedido
     */
    public function show($id)
    {
        $order = Order::with([
            'customer',
            'items',
            'statusEvents' => function ($q) {
                $q->orderByDesc('created_at');
            }
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Atualiza status interno do pedido
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::with('customer')->findOrFail($id);

        // status enviado tanto pelo select quanto pelos botões rápidos
        $data = $request->validate([
            'status'  => ['required', 'string'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        // Ajusta aqui pros status que você realmente usar
        $allowedStatuses = ['novo', 'em_separacao', 'enviado', 'entregue', 'cancelado'];

        if (! in_array($data['status'], $allowedStatuses, true)) {
            return back()->withErrors([
                'status' => 'Status inválido.',
            ]);
        }

        // Atualiza o status interno do pedido
        $oldStatus = $order->status;
        $order->status = $data['status'];
        $order->save();

        // Monta um comentário padrão se não veio nada
        $comment = $data['comment'] ?? null;
        if (! $comment) {
            $comment = 'Status alterado de "' . $oldStatus . '" para "' . $order->status . '" via painel admin.';
        }

        // Registra na timeline (order_status_events)
        OrderStatusEvent::create([
            'order_id' => $order->id,
            'status'   => $order->status,
            'source'   => 'admin',
            'comment'  => $comment,
        ]);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with(
                'status_message',
                'Status atualizado para "' . Str::upper(str_replace('_', ' ', $order->status)) . '".'
            );
    }
}
