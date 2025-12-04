<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus pedidos - Tech Orders</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .page {
            max-width: 960px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            padding: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
        .customer-info {
            font-size: 13px;
            color: #4b5563;
        }
        .customer-info strong {
            font-weight: 600;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            margin-top: 16px;
        }
        th, td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            text-align: left;
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
        .actions a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            font-size: 13px;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .empty {
            font-size: 14px;
            color: #4b5563;
            padding: 24px 0;
            text-align: center;
        }
        .logout-form {
            margin: 0;
        }
        .logout-button {
            border: none;
            background: #111827;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 999px;
            cursor: pointer;
        }
        .logout-button:hover {
            background: #000000;
        }
        .pagination {
            margin-top: 16px;
            font-size: 13px;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <h1 class="header-title">Meus pedidos</h1>
            <div class="header-subtitle">
                Olá, {{ $customer->name }} — aqui você acompanha os pedidos feitos na Tech Market Brasil.
            </div>
        </div>

        <div class="customer-info">
            <div><strong>E-mail:</strong> {{ $customer->email }}</div>
            @if($customer->phone)
                <div><strong>Telefone:</strong> {{ $customer->phone }}</div>
            @endif>

            <form class="logout-form" action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">Sair</button>
            </form>
        </div>
    </div>

    @if($orders->count() === 0)
        <div class="empty">
            Nenhum pedido encontrado para este cliente.<br>
            Se você realizou uma compra recentemente, aguarde alguns minutos e atualize a página.
        </div>
    @else
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Status interno</th>
                <th>Status pagamento</th>
                <th>Total</th>
                <th>Método</th>
                <th>Cidade</th>
                <th>Criado em</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
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
                        R$ {{ number_format($order->amount / 100, 2, ',', '.') }}
                    </td>
                    <td>{{ strtoupper($order->method ?? '—') }}</td>
                    <td>{{ $order->city ?? '—' }}</td>
                    <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="actions">
                        <a href="{{ route('customer.orders.show', $order->id) }}">Ver detalhes</a>
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
