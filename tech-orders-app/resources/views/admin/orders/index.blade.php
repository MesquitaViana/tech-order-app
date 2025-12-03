<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Tech Orders - Pedidos</title>
    <style>
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        h1 { margin-bottom: 20px; }
        .search-box { margin-bottom: 20px; }
        input[type="text"] { padding: 8px; width: 260px; }
        button { padding: 8px 12px; cursor: pointer; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; font-size: 14px; }
        th { text-align: left; background: #fafafa; }
        tr:hover { background: #f0f0f0; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .status { font-size: 12px; padding: 3px 8px; border-radius: 999px; background: #e5e7eb; display: inline-block; }
        .gateway { font-size: 11px; color: #6b7280; }
        .pagination { margin-top: 16px; font-size: 14px; }
        .pagination a { margin-right: 8px; }
    </style>
</head>
<body>
    <h1>Pedidos (Admin)</h1>

    <form method="GET" action="{{ route('admin.orders.index') }}" class="search-box">
        <input
            type="text"
            name="search"
            value="{{ $search ?? '' }}"
            placeholder="Buscar por e-mail, nome ou ID externo..."
        >
        <button type="submit">Buscar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Externo (Luna)</th>
                <th>Cliente</th>
                <th>E-mail</th>
                <th>Status interno</th>
                <th>Status gateway</th>
                <th>Valor</th>
                <th>Método</th>
                <th>Criado em</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse ($orders as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->external_id }}</td>
                <td>{{ $order->customer->name ?? '-' }}</td>
                <td>{{ $order->customer->email ?? '-' }}</td>
                <td><span class="status">{{ $order->status }}</span></td>
                <td><span class="gateway">{{ $order->gateway_status }}</span></td>
                <td>R$ {{ number_format($order->amount, 2, ',', '.') }}</td>
                <td>{{ strtoupper($order->method ?? '-') }}</td>
                <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                <td><a href="{{ route('admin.orders.show', $order->id) }}">Ver</a></td>
            </tr>
        @empty
            <tr>
                <td colspan="10">Nenhum pedido encontrado.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $orders->withQueryString()->links() }}
    </div>
</body>
</html>
