<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus pedidos - Tech Market Brasil</title>
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
            padding: 0 16px;
        }
        .card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(15,23,42,0.10);
            padding: 20px 20px 18px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 10px;
        }
        .title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }
        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }
        .customer-name {
            font-weight: 600;
            color: #111827;
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
            padding: 7px 12px;
            border-radius: 999px;
            cursor: pointer;
        }
        .logout-button:hover {
            background: #000000;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        th, td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            text-align: left;
        }
        th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
        }
        tr:hover {
            background: #f3f4f6;
        }
        .badge {
            display: inline-flex;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-status {
            background: #eef2ff;
            color: #3730a3;
        }
        .badge-paid {
            background: #ecfdf5;
            color: #166534;
        }
        .badge-pending {
            background: #fef9c3;
            color: #854d0e;
        }
        .badge-canceled {
            background: #fee2e2;
            color: #b91c1c;
        }
        .link {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }
        .link:hover {
            text-decoration: underline;
        }
        .empty {
            font-size: 14px;
            color: #4b5563;
            padding: 18px 0 6px;
        }
        .empty small {
            display: block;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="card">
        <div class="header">
            <div>
                <h1 class="title">Meus pedidos</h1>
                <p class="subtitle">
                    Olá, <span class="customer-name">{{ $customer->name }}</span>!<br>
                    Aqui você acompanha o andamento dos seus pedidos na Tech Market Brasil.
                </p>
            </div>
            <div>
                <form class="logout-form" method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Sair da conta</button>
                </form>
            </div>
        </div>

        @if($orders->count() === 0)
            <p class="empty">
                Você ainda não possui pedidos cadastrados com este e-mail/CPF.
                <small>Se você fez um pedido recentemente, aguarde alguns minutos e atualize esta página.</small>
            </p>
        @else
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Status do pedido</th>
                    <th>Status pagamento</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    @php
                        $gatewayStatus = $order->gateway_status ?? '';
                        $badgeClass = 'badge-pending';
                        if ($gatewayStatus === 'paid') $badgeClass = 'badge-paid';
                        if (in_array($gatewayStatus, ['canceled', 'refunded'])) $badgeClass = 'badge-canceled';
                    @endphp
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>
                        <td>
                            R$
                            {{ number_format(($order->amount ?? 0) / 100, 2, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge badge-status">
                                {{ strtoupper(str_replace('_', ' ', $order->status ?? '—')) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $badgeClass }}">
                                {{ strtoupper($gatewayStatus ?: '—') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="link">
                                Ver detalhes
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
</body>
</html>
