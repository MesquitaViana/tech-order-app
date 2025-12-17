<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusEvent;
use App\Models\TrackingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Lista de pedidos com busca e filtros
     */
    public function index(Request $request)
    {
        $search   = trim($request->input('q', ''));
        $status   = $request->input('status', '');
        $method   = $request->input('method', '');
        $dateFrom = $request->input('date_from', '');
        $dateTo   = $request->input('date_to', '');

        $statusOptions = [
            'novo'          => 'Novo',
            'em_separacao'  => 'Em separação',
            'enviado'       => 'Enviado',
            'entregue'      => 'Entregue',
            'cancelado'     => 'Cancelado',
        ];

        $query = Order::with('customer')
            ->orderByDesc('id');

        // Filtro por status interno
        if ($status !== '') {
            $query->where('status', $status);
        }

        // Filtro por método de pagamento
        if ($method !== '') {
            $query->where('method', $method);
        }

        /**
         * Filtros de data — CORRIGIDOS
         * Impedem que o SQLite quebre com strings vazias ou datas inválidas.
         */
        if (!empty($dateFrom) && strtotime($dateFrom) !== false) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if (!empty($dateTo) && strtotime($dateTo) !== false) {
            $query->whereDate('created_at', '<=', $dateTo);
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

        $orders = $query
            ->paginate(20)
            ->appends($request->query());

        // Métodos distintos para filtro
        $methods = Order::select('method')
            ->distinct()
            ->whereNotNull('method')
            ->orderBy('method')
            ->pluck('method')
            ->toArray();

        return view('admin.orders.index', [
            'orders'        => $orders,
            'search'        => $search,
            'status'        => $status,
            'method'        => $method,
            'dateFrom'      => $dateFrom,
            'dateTo'        => $dateTo,
            'statusOptions' => $statusOptions,
            'methods'       => $methods,
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
                    $q->orderBy('created_at', 'desc');
                },
                'trackingHistories' => function ($q) {
                    $q->orderBy('created_at', 'desc');
                },
                'trackingHistories.admin',
            ])
            ->findOrFail($id);

        $statusOptions = [
            'novo'          => 'Novo',
            'em_separacao'  => 'Em separação',
            'enviado'       => 'Enviado',
            'entregue'      => 'Entregue',
            'cancelado'     => 'Cancelado',
        ];

        return view('admin.orders.show', [
            'order'         => $order,
            'statusOptions' => $statusOptions,
        ]);
    }

    /**
     * Atualiza o status interno do pedido
     */
    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status'  => ['required', 'string'],
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        $validStatuses = ['novo', 'em_separacao', 'enviado', 'entregue', 'cancelado'];

        if (! in_array($data['status'], $validStatuses, true)) {
            return back()->withErrors(['status' => 'Status interno inválido.']);
        }

        $order = Order::findOrFail($id);

        $oldStatus = $order->status;
        $order->status = $data['status'];
        $order->save();

        // e-mail "em transporte" ao anexar rastreio
        if ($newCode !== '') {
            $order->load('customer');
            app(\App\Services\OrderEmailService::class)->pedidoEmTransporte($order, $newCode);
        }


        $comment = $data['comment'] ?: 'Status atualizado manualmente pelo admin.';

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
                'Status atualizado de "' . Str::upper(str_replace('_', ' ', $oldStatus)) .
                '" para "' . Str::upper(str_replace('_', ' ', $order->status)) . '".'
            );
    }

    /**
     * Atualiza o código de rastreio do pedido e registra histórico
     */
    public function updateTracking(Request $request, $id)
    {
        $data = $request->validate([
            'tracking_code' => ['nullable', 'string', 'max:255'],
        ]);

        $order = Order::findOrFail($id);

        $oldCode = (string) ($order->tracking_code ?? '');
        $newCode = trim((string) ($data['tracking_code'] ?? ''));

        // Se não mudou nada, não registra histórico
        if ($oldCode === $newCode) {
            return redirect()
                ->route('admin.orders.show', $order->id)
                ->with('status_message', 'Código de rastreio mantido sem alterações.');
        }

        $order->tracking_code = $newCode !== '' ? $newCode : null;
        $order->save();

        TrackingHistory::create([
            'order_id' => $order->id,
            'admin_id' => Auth::guard('admin')->id(),
            'old_code' => $oldCode !== '' ? $oldCode : null,
            'new_code' => $newCode !== '' ? $newCode : null,
        ]);

        return redirect()
            ->route('admin.orders.show', $order->id)
            ->with('status_message', 'Código de rastreio atualizado com sucesso.');
    }
}
