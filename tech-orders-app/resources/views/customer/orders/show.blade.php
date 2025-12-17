<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $order->id }} - Tech Market Brasil</title>
    <style>
    :root {
        --tech-navy: #070c2b;
        --tech-navy-soft: #0b123d;
        --tech-green: #8bc34a;
        --tech-green-dark: #6ea92f;
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
        max-width: 1100px;
        margin: 32px auto;
        padding: 0 16px;
    }

    .card {
        background: var(--white);
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(7,12,43,0.12);
        padding: 24px;
        margin-bottom: 24px;
    }

    /* HEADER */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 18px;
    }

    .title {
        font-size: 22px;
        font-weight: 800;
        margin: 4px 0 0;
        color: var(--tech-navy);
    }

    .subtitle {
        font-size: 14px;
        color: var(--tech-gray);
        margin-top: 8px;
        line-height: 1.5;
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

    /* LOGOUT */
    .header button {
        border: none;
        background: var(--tech-navy);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 999px;
        cursor: pointer;
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

    .badge-paid {
        background: rgba(139,195,74,.18);
        color: var(--tech-green-dark);
    }

    .badge-pending {
        background: rgba(234,179,8,.2);
        color: #854d0e;
    }

    .badge-canceled {
        background: rgba(239,68,68,.18);
        color: #b91c1c;
    }

    /* GRID */
    .grid {
        display: grid;
        grid-template-columns: 2fr 1.2fr;
        gap: 24px;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th, td {
        padding: 12px 10px;
        border-bottom: 1px solid var(--tech-border);
        font-size: 14px;
    }

    th {
        background: #f9fafb;
        color: var(--tech-navy);
        font-weight: 800;
    }

    tr:hover {
        background: #f5f7ff;
    }

    /* TEXT BLOCKS */
    .section-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--tech-navy);
        margin-bottom: 8px;
    }

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

    /* TIMELINE */
    ul.timeline {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    ul.timeline li {
        position: relative;
        padding-left: 22px;
        margin-bottom: 14px;
        font-size: 14px;
        color: #111827;
    }

    ul.timeline li::before {
        content: "";
        position: absolute;
        left: 6px;
        top: 6px;
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: var(--tech-green);
    }

    ul.timeline li span {
        display: block;
        font-size: 12px;
        color: var(--tech-gray);
        margin-top: 4px;
    }

    /* LINKS */
    .link {
        font-size: 14px;
        font-weight: 700;
        color: var(--tech-navy);
        text-decoration: none;
    }

    .link:hover {
        text-decoration: underline;
    }

    /* MODAL TERMOS */
    .tracking-terms-overlay {
        position: fixed;
        inset: 0;
        background: rgba(7,12,43,.75);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }

    .tracking-terms-modal {
        max-width: 560px;
        width: 100%;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 30px 70px rgba(7,12,43,.45);
        padding: 24px;
        display: flex;
        flex-direction: column;
        max-height: 80vh;
    }

    .tracking-terms-header h2 {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 14px;
        color: var(--tech-navy);
    }

    .tracking-terms-content {
        padding: 14px;
        border-radius: 12px;
        border: 1px solid var(--tech-border);
        background: #f9fafb;
        font-size: 14px;
        color: #374151;
        overflow-y: auto;
        max-height: 40vh;
        line-height: 1.6;
    }

    .tracking-terms-actions {
        margin-top: 14px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    #tracking-terms-accept-btn {
        border: none;
        border-radius: 999px;
        padding: 12px;
        font-size: 14px;
        font-weight: 800;
        cursor: not-allowed;
        background: #9ca3af;
        color: #f9fafb;
    }

    #tracking-terms-accept-btn.enabled {
        cursor: pointer;
        background: var(--tech-navy);
    }

    #tracking-terms-accept-btn.enabled:hover {
        background: #000;
    }

    .tracking-terms-helper {
        font-size: 12px;
        color: var(--tech-gray);
        text-align: center;
    }

    @media (max-width: 900px) {
        .grid {
            grid-template-columns: 1fr;
        }
    }
</style>

</head>
<body>
<div class="page">

    {{-- MODAL DE TERMOS DE ENTREGA: aparece só se tiver rastreio e ainda não tiver aceitado --}}
    @if($order->tracking_code && !$order->tracking_terms_accepted_at)
        <div id="tracking-terms-overlay" class="tracking-terms-overlay">
            <div class="tracking-terms-modal">
                <div class="tracking-terms-header">
                    <h2>Termos de Entrega — Tech Market Brasil</h2>
                </div>
                <div id="tracking-terms-content" class="tracking-terms-content">
                    <p>
                        Ao prosseguir com sua compra, você concorda que pedidos já enviados e com
                        código de rastreio anexado só poderão ser cancelados caso o cliente recuse a
                        entrega no momento da chegada ou devolva o produto à transportadora.
                    </p>
                    <p>
                        Para pedidos enviados pelos Correios, qualquer situação de extravio,
                        retenção, apreensão, atraso incomum ou problemas decorrentes da logística
                        dos Correios é de responsabilidade exclusiva do cliente, conforme descrito
                        em nossos termos oficiais. Nesses casos, não realizamos reenvio nem estorno.
                    </p>
                    <p>
                        Você pode consultar a política completa em:
                        <br>
                        👉
                        <a href="https://techmarketbrasil.com/envios-e-frete/" target="_blank">
                            https://techmarketbrasil.com/envios-e-frete/
                        </a>
                    </p>
                </div>

                <form id="tracking-terms-form"
                      action="{{ route('customer.orders.acceptTrackingTerms', $order->id) }}"
                      method="POST"
                      class="tracking-terms-actions">
                    @csrf
                    <button type="submit" id="tracking-terms-accept-btn" disabled>
                        Li e concordo com os termos de entrega
                    </button>
                    <p class="tracking-terms-helper">
                        Role o texto até o final para liberar o botão de aceite.
                    </p>
                </form>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="header">
            <div>
                <a href="{{ route('customer.orders') }}" class="back-link">← Voltar para meus pedidos</a>
                <h1 class="title">Pedido #{{ $order->id }}</h1>

                @php
                    $gatewayStatusRaw = strtolower($order->gateway_status ?? '');
                    $gatewayBadgeClass = 'badge-paid';
                    $gatewayLabel = null;

                    switch ($gatewayStatusRaw) {
                        case 'paid':
                            $gatewayBadgeClass = 'badge-paid';
                            $gatewayLabel = 'Pago';
                            break;
                        case 'pending':
                            $gatewayBadgeClass = 'badge-pending';
                            $gatewayLabel = 'Pendente';
                            break;
                        case 'canceled':
                            $gatewayBadgeClass = 'badge-canceled';
                            $gatewayLabel = 'Cancelado';
                            break;
                        case 'refunded':
                            $gatewayBadgeClass = 'badge-canceled';
                            $gatewayLabel = 'Reembolsado';
                            break;
                    }
                @endphp

                <p class="subtitle">
                    Realizado em {{ optional($order->created_at)->format('d/m/Y H:i') }} •
                    Status:
                    <span class="badge badge-status">
                        {{ strtoupper(str_replace('_', ' ', $order->status ?? '—')) }}
                    </span>
                    @if($gatewayLabel)
                        • Pagamento:
                        <span class="badge {{ $gatewayBadgeClass }}">
                            {{ $gatewayLabel }}
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
                    {{ $order->shipping_address_line ?: '-' }}
                </div>

                <div class="section-title" style="margin-top: 10px;">Resumo do pagamento</div>
                <div class="label">Valor total</div>
                <div class="value">
                    {{ $order->total_amount_br }}
                </div>

                <div class="label">Método</div>
                <div class="value">{{ strtoupper($order->method ?? '—') }}</div>
            </div>
        </div>
    </div>

    {{-- Comprovante de entrega (apenas quando status = entregue) --}}
    @if($order->status === 'entregue')
        <div class="card">
            <div class="section-title">Comprovante de entrega</div>

            <div class="label">Situação</div>
            <div class="value">
                Este pedido foi marcado como <strong>entregue</strong> no nosso sistema.
            </div>

            @if($order->delivered_at || $order->delivery_person_name)
                <div class="label" style="margin-top:8px;">Detalhes da entrega</div>
                <div class="value">
                    @if($order->delivered_at)
                        Entregue em {{ optional($order->delivered_at)->format('d/m/Y \à\s H:i') }}
                    @endif

                    @if($order->delivered_at && $order->delivery_person_name)
                        •
                    @endif

                    @if($order->delivery_person_name)
                        Responsável: {{ $order->delivery_person_name }}
                    @endif
                </div>
            @endif

            @if($order->delivery_proof_pin)
                <div class="label" style="margin-top:8px;">Código de confirmação (PIN)</div>
                <div class="value">
                    {{ $order->delivery_proof_pin }}
                    <span style="display:block;font-size:11px;color:#6b7280;margin-top:2px;">
                        Este é o código informado no ato da entrega para confirmar o recebimento.
                    </span>
                </div>
            @endif

            @if($order->delivery_proof_url)
                <div class="label" style="margin-top:8px;">Foto ou assinatura</div>
                <div class="value">
                    <a href="{{ $order->delivery_proof_url }}" target="_blank" class="link">
                        Ver comprovante de entrega
                    </a>
                    <span style="display:block;font-size:11px;color:#6b7280;margin-top:2px;">
                        Abriremos o comprovante de entrega em uma nova aba/janela.
                    </span>
                </div>
            @endif
        </div>
    @endif

    {{-- Linha do tempo do pedido --}}
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
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var content = document.getElementById('tracking-terms-content');
        var btn = document.getElementById('tracking-terms-accept-btn');

        if (!content || !btn) return;

        function checkScroll() {
            var bottom = content.scrollTop + content.clientHeight;
            if (bottom >= content.scrollHeight - 4) {
                btn.disabled = false;
                btn.classList.add('enabled');
            }
        }

        content.addEventListener('scroll', checkScroll);
        checkScroll();
    });
</script>
</body>
</html>
