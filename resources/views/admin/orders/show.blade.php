<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $order->id }} - Painel Admin | Tech Orders</title>
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
        padding: 28px;
    }

    /* HEADER */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
    }

    .header-title {
        font-size: 22px;
        font-weight: 800;
        margin: 4px 0 0;
        color: var(--tech-navy);
    }

    .header-subtitle {
        font-size: 14px;
        color: var(--tech-gray);
        margin-top: 6px;
        line-height: 1.4;
    }

    .back-link {
        font-size: 13px;
        font-weight: 700;
        color: var(--tech-navy);
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .logout-button {
        border: none;
        background: var(--tech-red);
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        padding: 8px 16px;
        border-radius: 999px;
        cursor: pointer;
    }

    .logout-button:hover {
        background: #b91c1c;
    }

    /* GRID */
    .grid {
        display: grid;
        grid-template-columns: 2.2fr 1.4fr;
        gap: 24px;
    }

    /* CARDS */
    .card {
        background: #f9fafb;
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 18px;
        border: 1px solid var(--tech-border);
    }

    .card-title {
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 10px;
        color: var(--tech-navy);
    }

    /* LABELS */
    .label {
        font-size: 12px;
        color: var(--tech-gray);
        margin-top: 10px;
    }

    .value {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        margin-top: 2px;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
    }

    th, td {
        padding: 10px 8px;
        border-bottom: 1px solid var(--tech-border);
        font-size: 14px;
    }

    th {
        background: #f1f5f9;
        color: var(--tech-navy);
        font-weight: 800;
    }

    tr:hover {
        background: #f5f7ff;
    }

    /* BADGES */
    .badge {
        display: inline-flex;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .badge-status {
        background: rgba(7,12,43,.08);
        color: var(--tech-navy);
    }

    .badge-gateway {
        background: rgba(139,195,74,.18);
        color: var(--tech-green-dark);
    }

    /* STATUS FEEDBACK */
    .status-success {
        color: var(--tech-green-dark);
        font-size: 14px;
        font-weight: 700;
        margin: 10px 0;
    }

    .status-error {
        color: #b91c1c;
        font-size: 14px;
        font-weight: 700;
        margin: 10px 0;
    }

    /* FORM */
    .status-form select,
    .status-form textarea,
    input[type="text"] {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid #d1d5db;
        font-size: 14px;
    }

    .status-form button,
    .btn-small {
        margin-top: 8px;
        padding: 10px 16px;
        border-radius: 999px;
        border: none;
        background: var(--tech-navy);
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
    }

    .status-form button:hover,
    .btn-small:hover {
        background: #000;
    }

    /* QUICK BUTTONS */
    .quick-buttons {
        display: flex;
        gap: 10px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .quick-button-enviado {
        background: #0369a1;
        color: #e0f2fe;
        padding: 8px 14px;
        border-radius: 999px;
        border: none;
        font-weight: 800;
        cursor: pointer;
    }

    .quick-button-entregue {
        background: var(--tech-green-dark);
        color: #dcfce7;
        padding: 8px 14px;
        border-radius: 999px;
        border: none;
        font-weight: 800;
        cursor: pointer;
    }

    /* TIMELINE */
    ul.timeline {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    ul.timeline li {
        font-size: 14px;
        padding: 6px 0;
        border-bottom: 1px solid var(--tech-border);
    }

    ul.timeline li span {
        font-size: 12px;
        color: var(--tech-gray);
    }

    /* MOBILE */
    @media (max-width: 900px) {
        .grid {
            grid-template-columns: 1fr;
        }
    }
</style>

</head>
<body>

<div class="page">

    <div class="header">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="back-link">← Voltar para pedidos</a>
            <h1 class="header-title">Pedido #{{ $order->id }}</h1>
            <div class="header-subtitle">
                ID externo: {{ $order->external_id ?? '—' }}
                • Status: <span class="badge badge-status">{{ $order->status ?? '—' }}</span>
                • Pagamento: <span class="badge badge-gateway">{{ $order->gateway_status ?? '—' }}</span>
            </div>
        </div>

        <div>
            <form class="logout-form" method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="logout-button">Sair</button>
            </form>
        </div>
    </div>

    <div class="grid">
        {{-- COLUNA ESQUERDA --}}
        <div>
            <div class="card">
                <div class="card-title">Itens do pedido</div>

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

        <div class="card">
    <div class="card-title">Cliente</div>

    <div class="label">Nome</div>
    <div class="value">{{ $order->customer->name ?? '—' }}</div>

    <div class="label">E-mail</div>
    <div class="value">{{ $order->customer->email ?? '—' }}</div>

    <div class="label">Telefone</div>
    <div class="value">{{ $order->customer->phone ?? '—' }}</div>

    @if ($order->customer)
    <p class="helper">
        <a href="{{ route('admin.subscriptions.create', ['customer_id' => $order->customer->id]) }}"
           class="btn-small">
            Registrar assinatura para este cliente
        </a>
    </p>

    {{-- ⭐ BLOCO: ASSINATURAS DO CLIENTE --}}
    <div style="margin-top:14px;">
        <div style="font-weight:800; margin-bottom:8px;">Assinaturas do cliente</div>

        @php
            $subs = $order->customer->subscriptions ?? collect();
        @endphp

        @if ($subs->isEmpty())
            <div style="font-size:13px; color:#6b7280;">
                Este cliente ainda não tem assinaturas cadastradas.
            </div>
        @else
            @foreach ($subs as $sub)
                @php
                    $st = $sub->status ?? '—';
                    $bg = $st === 'ativa' ? '#ecfdf5' : ($st === 'pausada' ? '#fffbeb' : '#fef2f2');
                    $bd = $st === 'ativa' ? '#a7f3d0' : ($st === 'pausada' ? '#fde68a' : '#fecaca');
                    $tx = $st === 'ativa' ? '#065f46' : ($st === 'pausada' ? '#92400e' : '#b91c1c');
                    $next = $sub->next_delivery_date
                        ? \Illuminate\Support\Carbon::parse($sub->next_delivery_date)->format('d/m/Y')
                        : '—';
                @endphp

                <div style="border:1px solid #e5e7eb; border-radius:10px; padding:10px; background:#fff; margin-bottom:10px;">
                    <div style="display:flex; justify-content:space-between; gap:10px; align-items:flex-start;">
                        <div>
                            <div style="font-weight:800; color:#111827;">
                                {{ $sub->product_name }}
                                @if($sub->flavor)
                                    <span style="font-weight:600; color:#6b7280;">· {{ $sub->flavor }}</span>
                                @endif
                            </div>

                            <div style="font-size:13px; color:#374151; margin-top:6px;">
                                Qtd: <strong>{{ $sub->quantity }}</strong>
                                · Frequência: <strong>{{ $sub->frequency }}</strong>
                                · Próxima: <strong>{{ $next }}</strong>
                            </div>
                        </div>

                        <div style="display:flex; flex-direction:column; align-items:flex-end; gap:8px;">
                            <span style="padding:5px 10px; border-radius:999px; font-size:12px; font-weight:900; border:1px solid {{ $bd }}; background:{{ $bg }}; color:{{ $tx }};">
                                {{ $st }}
                            </span>

                            <a href="{{ route('admin.subscriptions.edit', $sub->id) }}"
                               style="text-decoration:none; font-size:13px; font-weight:800; color:#2563eb;">
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    {{-- ⭐ FIM --}}
@endif

</div>



            <div class="card">
                <div class="card-title">Endereço de entrega</div>
                <div class="value">
                    {{ $order->shipping_address_line ?: '-' }}
                </div>
            </div>
        </div>

        {{-- COLUNA DIREITA --}}
        <div>
            {{-- STATUS DO PEDIDO --}}
            <div class="card">
                <div class="card-title">Status do pedido</div>

                {{-- ✅ VALOR (ADICIONADO) --}}
                <div class="label" style="margin-top: 8px;">Valor total</div>
                <div class="value">
                    {{ $order->total_amount_br }}
                </div>

                @if(session('status_message'))
                    <p class="status-success">
                        {{ session('status_message') }}
                    </p>
                @endif

                @if($errors->has('status'))
                    <p class="status-error">
                        {{ $errors->first('status') }}
                    </p>
                @endif

                <form method="POST"
                      action="{{ route('admin.orders.updateStatus', $order->id) }}"
                      class="status-form">
                    @csrf

                    <label for="status" class="label">Alterar status:</label>
                    <select name="status" id="status">
                        @php
                            $options = ['novo', 'em_separacao', 'enviado', 'entregue', 'cancelado'];
                        @endphp
                        @foreach($options as $opt)
                            <option value="{{ $opt }}" @if($order->status === $opt) selected @endif>
                                {{ strtoupper(str_replace('_', ' ', $opt)) }}
                            </option>
                        @endforeach
                    </select>

                    <label for="comment" class="label">Comentário (opcional):</label>
                    <textarea name="comment" id="comment" rows="3"
                              placeholder="Ex: Pedido pronto para envio, aguardando coleta da transportadora."></textarea>

                    <button type="submit">Salvar status</button>
                </form>

                <div class="label" style="margin-top: 10px;">Botões rápidos:</div>
                <div class="quick-buttons">
                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="enviado">
                        <input type="hidden" name="comment"
                               value="Status atualizado rapidamente para ENVIADO via botão rápido.">
                        <button type="submit" class="quick-button-enviado">
                            Marcar como ENVIADO
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                        @csrf
                        <input type="hidden" name="status" value="entregue">
                        <input type="hidden" name="comment"
                               value="Status atualizado rapidamente para ENTREGUE via botão rápido.">
                        <button type="submit" class="quick-button-entregue">
                            Marcar como ENTREGUE
                        </button>
                    </form>
                </div>
            </div>

            {{-- CARD DE RASTREIO --}}
            <div class="card">
                <div class="card-title">Rastreio</div>
                <div class="label">Código utilizado para acompanhar o envio do pedido.</div>

                <form action="{{ route('admin.orders.updateTracking', $order->id) }}"
                      method="POST"
                      style="display:flex; flex-wrap:wrap; gap:8px; align-items:center; margin-top:8px;">
                    @csrf
                    <input
                        type="text"
                        name="tracking_code"
                        value="{{ old('tracking_code', $order->tracking_code) }}"
                        placeholder="Ex.: LX123456789BR"
                        style="flex: 1 1 220px; padding: 8px 10px; border-radius: 8px; border: 1px solid #d1d5db; font-size: 13px;"
                    >
                    <button type="submit"
                            style="padding: 8px 14px; border-radius: 999px; border:none; background:#111827; color:#ffffff; font-size:13px; font-weight:600; cursor:pointer;">
                        Salvar rastreio
                    </button>
                </form>

                <p style="font-size: 11px; color:#6b7280; margin-top:6px;">
                    Sempre que você alterar o código de rastreio, registraremos um histórico com o admin responsável e o horário.
                </p>
            </div>

            {{-- HISTÓRICO DE RASTREIO --}}
            @if($order->trackingHistories && $order->trackingHistories->count())
                <div class="card">
                    <div class="card-title">Histórico de rastreio</div>
                    <div class="label">
                        Todas as alterações realizadas no código de rastreio deste pedido.
                    </div>

                    <ul class="timeline" style="margin-top:8px;">
                        @foreach($order->trackingHistories as $history)
                            <li style="padding:4px 0; border-bottom:1px solid #e5e7eb;">
                                <strong>{{ optional($history->created_at)->format('d/m/Y H:i') }}</strong>
                                —
                                @php
                                    $adminName = $history->admin?->name ?? 'Admin (excluído)';
                                @endphp
                                <span style="color:#6b7280;">{{ $adminName }}</span> alterou:
                                <span style="color:#6b7280;">
                                    {{ $history->old_code ?: 'sem código' }} →
                                </span>
                                <span style="font-weight:600;">
                                    {{ $history->new_code ?: 'sem código' }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- HISTÓRICO DE STATUS --}}
            <div class="card">
                <div class="card-title">Histórico de status</div>
                @if($order->statusEvents->count())
                    <ul class="timeline">
                        @foreach ($order->statusEvents as $event)
                            <li>
                                <span>[{{ $event->created_at?->format('d/m/Y H:i') }}]</span>
                                <strong>{{ strtoupper(str_replace('_', ' ', $event->status)) }}</strong>
                                — {{ $event->comment }} ({{ $event->source }})
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="value">Nenhum evento registrado.</div>
                @endif
            </div>
        </div>
    </div>
</div>

</body>
</html>
