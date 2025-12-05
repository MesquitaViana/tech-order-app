<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $order->id }} - Tech Market Brasil</title>
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
            margin-bottom: 16px;
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
        .back-link {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 6px;
        }
        .back-link:hover {
            text-decoration: underline;
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
        .grid {
            display: grid;
            grid-template-columns: 2fr 1.2fr;
            gap: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        th, td {
            padding: 6px 4px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            text-align: left;
        }
        th {
            background: #f9fafb;
            color: #374151;
            font-weight: 600;
        }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #374151;
        }
        .label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 3px;
        }
        .value {
            font-size: 13px;
            color: #111827;
            margin-bottom: 4px;
        }
        ul.timeline {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        ul.timeline li {
            position: relative;
            padding-left: 18px;
            margin-bottom: 8px;
            font-size: 13px;
            color: #374151;
        }
        ul.timeline li::before {
            content: "";
            position: absolute;
            left: 4px;
            top: 4px;
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #16a34a;
        }
        ul.timeline li span {
            display: block;
            font-size: 11px;
            color: #9ca3af;
        }
        .helper {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }
    </style>
</head>
<body>
<div class="page">

    <div class="card">
        <div class="header">
            <div>
                <a href="{{ route('customer.orders') }}" class="back-link">← Voltar para meus pedidos</a>
                <h1 class="title">Pedido #{{ $order->id }}</h1>
                <p class="subtitle">
                    Realizado em {{ optional($order->created_at)->format('d/m/Y H:i') }} •
                    Status:
                    <span class="badge badge-status">
                        {{ strtoupper(str_replace('_', ' ', $order->status ?? '—')) }}
                    </span>
                    @if($order->gateway_status)
                        • Pagamento:
                        <span class="badge badge-paid">
                            {{ strtoupper($order->gateway_status) }}
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit"
                            style="border:none;background:#111827;color:#fff;font-size:12px;font-weight:600;padding:7px 12px;border-radius:999px;cursor:pointer;">
                        Sair
                    </button>
                </form>
            </div>
        </div>

        <div class="grid">
            <div>
                <div class="section-title">Itens do pedido</div>
                <table>
                    <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Preço</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>R$ {{ number_format($item->price / 100, 2, ',', '.') }}</td>
                            <td>R$ {{ number_format(($item->price * $item->quantity) / 100, 2, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                <div class="section-title">Endereço de entrega</div>
                <div class="value">
                    {{ $order->address_street ?? '' }}
                    {{ $order->address_number ?? '' }}<br>
                    {{ $order->address_neighborhood ?? '' }}<br>
                    {{ $order->address_city ?? '' }} - {{ $order->address_state ?? '' }}<br>
                    CEP: {{ $order->address_zipcode ?? '' }}
                </div>

                <div class="section-title" style="margin-top: 10px;">Resumo do pagamento</div>
                <div class="label">Valor total</div>
                <div class="value">
                    R$
                    {{ number_format(($order->amount ?? 0) / 100, 2, ',', '.') }}
                </div>
                <div class="label">Método</div>
                <div class="value">{{ strtoupper($order->method ?? '—') }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="section-title">Linha do tempo do pedido</div>
        @if($order->statusEvents->count())
            <ul class="timeline">
                @foreach($order->statusEvents as $event)
                    @php
                        $label = match($event->status) {
                            'novo'          => 'Pedido recebido pela Tech Market Brasil.',
                            'em_separacao'  => 'Seu pedido está em separação no centro de distribuição.',
                            'enviado'       => 'Pedido enviado para a transportadora / Correios.',
                            'entregue'      => 'Pedido entregue no endereço informado.',
                            'cancelado'     => 'Pedido cancelado.',
                            default         => strtoupper($event->status),
                        };
                    @endphp
                    <li>
                        {{ $label }}
                        @if($event->comment)
                            — {{ $event->comment }}
                        @endif
                        <span>
                            {{ $event->created_at?->format('d/m/Y H:i') }}
                            • origem: {{ $event->source }}
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="value">Ainda não há histórico de movimentação para este pedido.</div>
        @endif
        <p class="helper">
            Qualquer dúvida sobre prazos ou rastreio, fale com nosso suporte informando o número deste pedido.
        </p>
    </div>
</div>
</body>
</html>
