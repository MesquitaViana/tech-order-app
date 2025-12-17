<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos - Painel Admin | Tech Orders</title>
    <style>
    :root {
        --tech-navy: #070c2b;
        --tech-navy-soft: #0b123d;
        --tech-green: #8bc34a;
        --tech-green-dark: #6ea92f;
        --tech-red: #ef4444;
        --tech-yellow: #facc15;
        --tech-gray: #6b7280;
        --tech-border: #e5e7eb;
        --tech-bg: #f4f6fb;
        --white: #ffffff;
    }

    body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        background: var(--tech-bg);
        margin: 0;
        padding: 0;
        color: #111827;
    }

    .page {
        max-width: 1200px;
        margin: 32px auto;
        background: var(--white);
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(7,12,43,0.12);
        padding: 28px 30px 32px;
    }

    /* HEADER */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 22px;
    }

    .title {
        font-size: 22px;
        font-weight: 800;
        color: var(--tech-navy);
    }

    .subtitle {
        font-size: 14px;
        color: var(--tech-gray);
        margin-top: 6px;
    }

    .top-right {
        text-align: right;
        font-size: 13px;
        color: var(--tech-gray);
    }

    .top-right a {
        color: var(--tech-navy);
        font-weight: 700;
        text-decoration: none;
    }

    .top-right a:hover {
        text-decoration: underline;
    }

    /* FILTERS */
    .filters-card {
        background: #f9fafb;
        border-radius: 16px;
        padding: 16px 18px;
        border: 1px solid var(--tech-border);
        margin-bottom: 20px;
    }

    .filters-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--tech-navy);
        margin-bottom: 10px;
    }

    .search-box {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .search-box input,
    .search-box select {
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        min-width: 150px;
    }

    .search-box button {
        padding: 10px 18px;
        border-radius: 999px;
        border: none;
        background: var(--tech-navy);
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
    }

    .search-box button:hover {
        background: #000000;
    }

    .clear-link {
        font-size: 13px;
        color: var(--tech-gray);
        text-decoration: none;
        margin-left: 6px;
    }

    .clear-link:hover {
        text-decoration: underline;
    }

    .search-hint {
        font-size: 12px;
        color: var(--tech-gray);
        margin-top: 6px;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    th, td {
        padding: 12px 10px;
        font-size: 14px;
        border-bottom: 1px solid var(--tech-border);
        vertical-align: middle;
    }

    th {
        text-align: left;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--tech-navy);
        background: #f1f5f9;
        font-weight: 800;
    }

    tr:hover td {
        background: #f5f7ff;
    }

    .col-id {
        width: 60px;
        white-space: nowrap;
    }

    .col-external {
        width: 160px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .col-customer-name {
        font-weight: 700;
        color: #111827;
    }

    .col-customer-email {
        font-size: 12px;
        color: var(--tech-gray);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .col-amount {
        width: 120px;
        text-align: right;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    .col-date {
        width: 150px;
        white-space: nowrap;
        font-variant-numeric: tabular-nums;
    }

    .col-actions {
        width: 90px;
        text-align: right;
    }

    /* BADGES */
    .badge {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .badge-status-novo {
        background: rgba(7,12,43,.1);
        color: var(--tech-navy);
    }

    .badge-status-em_separacao {
        background: rgba(6,182,212,.15);
        color: #0e7490;
    }

    .badge-status-enviado {
        background: rgba(250,204,21,.25);
        color: #92400e;
    }

    .badge-status-entregue {
        background: rgba(139,195,74,.25);
        color: var(--tech-green-dark);
    }

    .badge-status-cancelado {
        background: rgba(239,68,68,.2);
        color: #b91c1c;
    }

    .badge-gateway-paid {
        background: rgba(139,195,74,.25);
        color: var(--tech-green-dark);
    }

    .badge-gateway-pending {
        background: rgba(250,204,21,.25);
        color: #92400e;
    }

    .badge-gateway-canceled,
    .badge-gateway-refunded {
        background: rgba(239,68,68,.25);
        color: #b91c1c;
    }

    .badge-gateway-default {
        background: #e5e7eb;
        color: #374151;
    }

    /* BUTTON */
    .btn-small {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        border: none;
        background: var(--tech-navy);
        color: #ffffff;
        text-decoration: none;
    }

    .btn-small:hover {
        background: #000000;
    }

    /* PAGINATION */
    .pagination-wrapper {
        margin-top: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .pagination-summary {
        font-size: 13px;
        color: #374151;
    }

    .pagination-links {
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .page {
            padding: 20px;
        }
        table {
            font-size: 13px;
        }
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
                        <option value="{{ $key }}" @selected($status === $key)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <select name="method">
                    <option value="">Método de pagamento</option>
                    @foreach($methods as $m)
                        <option value="{{ $m }}" @selected($method === $m)>
                            {{ strtoupper($m) }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ $dateFrom }}">
                <input type="date" name="date_to" value="{{ $dateTo }}">

                <button type="submit">Aplicar filtros</button>

                @if(request()->query())
                    <a href="{{ route('admin.orders.index') }}" class="clear-link">
                        Limpar filtros
                    </a>
                @endif
            </div>

            <div class="search-hint">
                Dica: combine termos de busca (ex.: nome do cliente + status + intervalo de datas)
                para refinar a listagem.
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
                            <div class="col-customer-email"
                                 title="{{ $order->customer->email }}">
                                {{ $order->customer->email }}
                            </div>
                        @else
                            <em>Cliente não vinculado</em>
                        @endif
                    </td>

                    <td class="col-amount">
                        {{ $order->total_amount_br }}
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
                        {{ strtoupper($order->payment_method_label) }}
                    </td>

                    <td class="col-date">
                        {{ optional($order->created_at)->format('d/m/Y H:i') }}
                    </td>

                    <td class="col-actions">
                        <a href="{{ route('admin.orders.show', $order->id) }}"
                           class="btn-small">
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
            Nenhum pedido encontrado com os filtros atuais.
            Ajuste os filtros ou limpe a pesquisa para ver todos os pedidos.
        </p>
    @endif
</div>
</body>
</html>
