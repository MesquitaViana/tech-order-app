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
            border-radius: 16px;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.12);
            padding: 24px 28px 28px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }
        .title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
        }
        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }
        .filters-card {
            background: #f9fafb;
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            margin-bottom: 16px;
        }
        .filters-title {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }
        .search-box {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .search-box input[type="text"],
        .search-box select,
        .search-box input[type="date"] {
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 13px;
            min-width: 140px;
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
        .search-box a.clear-link {
            font-size: 12px;
            color: #6b7280;
            text-decoration: none;
            margin-left: 4px;
        }
        .search-box a.clear-link:hover {
            text-decoration: underline;
        }
        .search-hint {
            font-size: 11px;
            color: #6b7280;
            margin-top: 4px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            padding: 8px 10px;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            background: #f9fafb;
        }
        tr:hover td {
            background: #f9fafb;
        }
        .col-id {
            width: 60px;
            white-space: nowrap;
        }
        .col-external {
            width: 140px;
            max-width: 160px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .col-customer {
            max-width: 260px;
        }
        .col-customer-name {
            font-weight: 600;
            color: #111827;
        }
        .col-customer-email {
            font-size: 11px;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .col-amount {
            width: 110px;
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        .col-date {
            width: 140px;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
        }
        .col-actions {
            width: 80px;
            text-align: right;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        /* Status interno */
        .badge-status-novo {
            background: #eff6ff;
            color: #1d4ed8;
        }
        .badge-status-em_separacao {
            background: #ecfeff;
            color: #0891b2;
        }
        .badge-status-enviado {
            background: #fef3c7;
            color: #d97706;
        }
        .badge-status-entregue {
            background: #dcfce7;
            color: #15803d;
        }
        .badge-status-cancelado {
            background: #fee2e2;
            color: #b91c1c;
        }
        /* Gateway status */
        .badge-gateway-paid {
            background: #dcfce7;
            color: #15803d;
        }
        .badge-gateway-pending {
            background: #fef9c3;
            color: #a16207;
        }
        .badge-gateway-canceled,
        .badge-gateway-refunded {
            background: #fee2e2;
            color: #b91c1c;
        }
        .badge-gateway-default {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-small {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            border: 1px solid #111827;
            background: #111827;
            color: #ffffff;
            text-decoration: none;
        }
        .btn-small:hover {
            background: #000000;
        }
        .pagination-wrapper {
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .pagination-summary {
            font-size: 12px;
            color: #4b5563;
        }
        .pagination-links {
            font-size: 12px;
        }
        .top-right {
            text-align: right;
            font-size: 12px;
            color: #6b7280;
        }
        .top-right a {
            color: #111827;
            text-decoration: none;
            font-weight: 600;
        }
        .top-right a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <div class="title">Pedidos</div>
            <div class="subtitle">
                Visão geral dos pedidos integrados pela Luna Checkout para a Tech Market Brasil.
            </div>
        </div>
        <div class="top-right">
            Painel Tech Orders<br>
            <a href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Sair do admin
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <div class="filters-card">
        <div class="filters-title">Filtros de pesquisa</div>
        <form method="GET" action="{{ route('admin.orders.index') }}">
            <div class="search-box">
                <input
                    type="text"
                    name="q"
                    placeholder="Buscar por ID, nome, e-mail ou ID externo..."
                    value="{{ $search }}"
                >

                <select name="status">
                    <option value="">Status interno</option>
                    @foreach($statusOptions as $key => $label)
                        <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="method">
                    <option value="">Método de pagamento</option>
                    @foreach($methods as $m)
                        <option value="{{ $m }}" @selected($method === $m)>{{ strtoupper($m) }}</option>
                    @endforeach
                </select>

                <input
                    type="date"
                    name="date_from"
                    value="{{ $dateFrom }}"
                    placeholder="De"
                >
                <input
                    type="date"
                    name="date_to"
                    value="{{ $dateTo }}"
                    placeholder="Até"
                >

                <button type="submit">Aplicar filtros</button>

                @if(request()->query())
                    <a href="{{ route('admin.orders.index') }}" class="clear-link">Limpar filtros</a>
                @endif
            </div>

            <div class="search-hint">
                Dica: combine termos de busca (ex.: nome do cliente + status + intervalo de datas) para refinar a listagem.
            </div>
        </form>
    </div>

    @if($orders->count())
        <table>
            <thead>
            <tr>
                <th class="col-id">#</th>
                <th class="col-external">ID Externo (Luna)</th>
                <th>Cliente</th>
                <th class="col-amount">Valor</th>
                <th>Status interno</th>
                <th>Status gateway</th>
                <th>Método</th>
                <th class="col-date">Criado em</th>
                <th class="col-actions"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                @php
                    $statusClass = 'badge-status-' . $order->status;
                    $gatewayStatus = strtolower($order->gateway_status ?? '');
                    $gatewayClass = match ($gatewayStatus) {
                        'paid'      => 'badge-gateway-paid',
                        'pending'   => 'badge-gateway-pending',
                        'canceled'  => 'badge-gateway-canceled',
                        'refunded'  => 'badge-gateway-refunded',
                        default     => 'badge-gateway-default',
                    };
                @endphp
                <tr>
                    <td class="col-id">#{{ $order->id }}</td>
                    <td class="col-external" title="{{ $order->external_id }}">
                        {{ $order->external_id }}
                    </td>
                    <td class="col-customer">
                        @if($order->customer)
                            <div class="col-customer-name">
                                {{ $order->customer->name }}
                            </div>
                            <div class="col-customer-email" title="{{ $order->customer->email }}">
                                {{ $order->customer->email }}
                            </div>
                        @else
                            <em>Cliente não vinculado</em>
                        @endif
                    </td>
                    <td class="col-amount">
                        R$ {{ number_format($order->amount, 2, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge {{ $statusClass }}">
                            {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $gatewayClass }}">
                            {{ strtoupper($order->gateway_status ?? '-') }}
                        </span>
                    </td>
                    <td>
                        {{ strtoupper($order->method ?? '-') }}
                    </td>
                    <td class="col-date">
                        {{ optional($order->created_at)->format('d/m/Y H:i') }}
                    </td>
                    <td class="col-actions">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-small">
                            Ver
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">
            <div class="pagination-summary">
                Mostrando
                {{ $orders->firstItem() }}–{{ $orders->lastItem() }}
                de {{ $orders->total() }} pedidos
            </div>
            <div class="pagination-links">
                {{ $orders->links() }}
            </div>
        </div>
    @else
        <p style="font-size: 13px; color: #6b7280; margin-top: 16px;">
            Nenhum pedido encontrado com os filtros atuais. Ajuste os filtros ou limpe a pesquisa para ver todos os pedidos.
        </p>
    @endif
</div>
</body>
</html>
