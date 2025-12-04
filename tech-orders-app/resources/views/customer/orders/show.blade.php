<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhe do pedido #{{ $order->id }} - Tech Orders</title>
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
            align-items: flex-start;
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
        .back-link a {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 20px;
            margin-top: 10px;
        }
        .card {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 16px;
            background: #ffffff;
        }
        .card-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .card-line {
            font-size: 13px;
            margin-bottom: 6px;
            color: #374151;
        }
        .card-line strong {
            font-weight: 600;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        th, td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            text-align: left;
        }
        th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }
        .timeline {
            list-style: none;
            padding-left: 0;
            margin: 0;
            font-size: 13px;
        }
        .timeline li {
            padding: 6px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .timeline li:last-child {
            border-bottom: none;
        }
        .timeline time {
            font-size: 12px;
            color: #6b7280;
            display: block;
        }
        .timeline strong {
            display: inline-block;
            margin-right: 4px;
        }
        .empty {
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <div class="back-link">
                <a href="{{ route('customer.orders') }}">← Voltar para meus pedidos</a>
            </div>
            <h1 class="header-title">Pedido #{{ $order->id }}</h1>
            <div class="header-subtitle">
                {{ $customer->name }} — {{ $customer->email }}
            </div>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <div class="card-title">Resumo do pedido</div>
            <div class="card-line">
                <strong>Status interno:</strong>
                <span class="badge badge-status">{{ $order->status ?? '—' }}</span>
            </div>
            <div class="card-line">
                <strong>Status pagamento:</strong>
                <span class="badge badge-gateway">{{ $order->gateway_status ?? '—' }}</span>
            </div>
            <div class="card-line">
                <strong>Total:</strong>
                R$ {{ number_format($order->amount / 100, 2, ',', '.') }}
            </div>
            <div class="card-line">
                <strong>Método de pagamento:</strong> {{ strtoupper($order->method ?? '—') }}
            </div>
            <div class="card-line">
                <strong>Cidade/UF:</strong>
                {{ $order->city ?? '—' }}@if($order->state) / {{ $order->state }}@endif
            </div>
            <div class="card-line">
                <strong>Criado em:</strong> {{ optional($order->created_at)->format('d/m/Y H:i') }}
            </div>
        </div>

        <div class="card">
            <div class="card-title">Histórico de status</div>
            @if($order->statusEvents->count())
                <ul class="timeline">
                    @foreach($order->statusEvents as $event)
                        <li>
                            <time>{{ optional($event->created_at)->format('d/m/Y H:i') }}</time>
                            <strong>{{ $event->status }}</strong>
                            @if($event->comment)
                                — {{ $event->comment }}
                            @endif
                            @if($event->source)
                                <span style="color:#6b7280;">({{ $event->source }})</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="empty">Nenhum evento de status registrado ainda.</div>
            @endif
        </div>
    </div>

    <div class="card" style="margin-top: 20px;">
        <div class="card-title">Itens do pedido</div>
        @if($order->items->count())
            <table>
                <thead>
                <tr>
                    <th>Produto</th>
                    <th>Descrição</th>
                    <th>Qtd</th>
                    <th>Preço</th>
                </tr>
                </thead>
                <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>R$ {{ number_format($item->price / 100, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="empty">Nenhum item registrado neste pedido.</div>
        @endif
    </div>
</div>
</body>
</html>
