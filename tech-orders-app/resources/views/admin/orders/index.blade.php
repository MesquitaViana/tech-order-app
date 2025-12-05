<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Painel Admin | Tech Orders</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .page {
            max-width: 1100px;
            margin: 32px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            padding: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }
        .header-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }
        .header-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }
        .search-box {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        .search-box input[type="text"],
        .search-box select {
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 13px;
        }
        .search-box button {
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            background: #111827;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .search-box button:hover {
            background: #000000;
        }
        .search-hint {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
        tr:hover {
            background: #f3f4f6;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-status {
            background: #eef2ff;
            color: #3730a3;
        }
        .badge-gateway {
            background: #ecfdf5;
            color: #166534;
        }
        .link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        .link:hover {
            text-decoration: underline;
        }
        .pagination {
            margin-top: 16px;
            font-size: 13px;
        }
        .logout-form {
            margin: 0;
        }
        .logout-button {
            border: none;
            background: #ef4444;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 999px;
            cursor: pointer;
        }
        .logout-button:hover {
            background: #b91c1c;
        }
        .empty {
            font-size: 14px;
            color: #4b5563;
            padding: 24px 0;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="page">
    <div class="header">
        <div>
            <h1 class="header-title">Pedidos</h1>
            <div class="header-subtitle">
                Visualize e gerencie os pedidos recebidos pela Tech Market Brasil.
            </div>

            <form method="GET" action="{{ route('admin.orders.index') }}" class="search-box">
                <input
                    type="text"
                    name="q"
                    placeholder="Buscar por e-mail, nome ou ID externo..."
                    value="{{ $search }}"
                >

                <select name="status">
                    <option value="">Status (todos)</option>
                    @php
                        $statusOptions = ['novo', 'em_separacao', 'enviado', 'entregue', 'cancelado'];
                    @endphp
                    @foreach($statusOptions as $opt)
                        <option value="{{ $opt }}" @if($status === $opt) selected @endif>
                            {{ strtoupper(str_replace('_', ' ', $opt)) }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Filtrar</button>
            </form>

            <div class="search-hint">
                Dica: você pode buscar pelo <strong>e-mail do cliente</strong>, <strong>nome</strong> ou
                <strong>ID externo</strong> da Luna.
            </div>
        </div>

        <div>
            <form class="logout-form" method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="logout-button">Sair</button>
            </form>
        </div>
    </div>

    @if($orders->count() === 0)
        <div class="empty">
            Nenhum pedido encontrado para os filtros informados.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>ID Externo</th>
                    <th>Cliente</th>
                    <th>E-mail</th>
                    <th>Status interno</th>
                    <th>Status pagamento</th>
                    <th>Total</th>
                    <th>Método</th>
                    <th>Criado em</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ $order->external_id ?? '—' }}</td>
                    <td>{{ $order->customer->name ?? '—' }}</td>
                    <td>{{ $order->customer->email ?? '—' }}</td>
                    <td>
                        <span class="badge badge-status">
                            {{ $order->status ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-gateway">
                            {{ $order->gateway_status ?? '—' }}
                        </span>
                    </td>
                    <td>
                        @php
                            // ajusta aqui se amount não for em centavos
                            $amount = $order->amount ?? 0;
                        @endphp
                        R$ {{ number_format($amount / 100, 2, ',', '.') }}
                    </td>
                    <td>{{ strtoupper($order->method ?? '—') }}</td>
                    <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="link">
                            Ver detalhes
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $orders->links() }}
        </div>
    @endif
</div>

</body>
</html>
