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
        .badge-pending {
            background: #fef9c3;
            color: #92400e;
        }
        .badge-canceled {
            background: #fee2e2;
            color: #991b1b;
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
        .link {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }

        /* ===== CSS DO MODAL DE TERMOS DE ENTREGA ===== */
        .tracking-terms-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .tracking-terms-modal {
            max-width: 560px;
            width: 100%;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 25px 60px rgba(15, 23, 42, 0.35);
            padding: 20px 22px 18px;
            display: flex;
            flex-direction: column;
            max-height: 80vh;
        }
        .tracking-terms-header h2 {
            font-size: 18px;
            margin: 0 0 12px;
            color: #111827;
        }
        .tracking-terms-content {
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            font-size: 13px;
            color: #374151;
            overflow-y: auto;
            max-height: 40vh;
        }
        .tracking-terms-content p {
            margin: 0 0 10px;
            line-height: 1.5;
        }
        .tracking-terms-content a {
            color: #111827;
            font-weight: 600;
            text-decoration: none;
        }
        .tracking-terms-content a:hover {
            text-decoration: underline;
        }
        .tracking-terms-actions {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            gap: 6px;
        }
        #tracking-terms-accept-btn {
            border: none;
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 600;
            cursor: not-allowed;
            background: #9ca3af;
            color: #f9fafb;
            transition: background 0.15s ease;
        }
        #tracking-terms-accept-btn.enabled {
            cursor: pointer;
            background: #111827;
        }
        #tracking-terms-accept-btn.enabled:hover {
            background: #000000;
        }
        .tracking-terms-helper {
            font-size: 11px;
            color: #6b7280;
            margin: 0;
        }
        /* ===== FIM CSS MODAL ===== */
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

            @if(!$order->delivered_at && !$order->delivery_person_name && !$order->delivery_proof_pin && !$order->delivery_proof_url)
                <p class="helper">
                    Seu pedido foi marcado como entregue. Caso não tenha recebido ou precise de mais detalhes,
                    fale com o suporte informando o número <strong>#{{ $order->id }}</strong>.
                </p>
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

    {{-- Bloco "Fale com o suporte" --}}
    <div class="card">
        <div class="section-title">Fale com o suporte</div>
        <p class="helper">
            Ficou com alguma dúvida sobre prazos, rastreio ou pagamento deste pedido?
        </p>
        <p class="helper">
            Entre em contato com o suporte da <strong>Tech Market Brasil</strong> informando
            o número <strong>#{{ $order->id }}</strong> e o e-mail usado no checkout.
        </p>
        <p class="helper">
            Canais oficiais:
            <br>
            • WhatsApp (link disponível no site oficial)<br>
            • E-mail de atendimento informado nos e-mails de confirmação de pedido
        </p>
        {{-- Se tiver link fixo do WhatsApp, substituir abaixo --}}
        {{-- <p class="helper">
            Ou clique aqui para falar direto no WhatsApp:
            <a href="https://wa.me/55XXXXXXXXXXX" target="_blank" class="link">
                Abrir WhatsApp da Tech Market Brasil
            </a>
        </p> --}}
    </div>

</div>

{{-- JS: só libera o botão ao rolar até o fim do texto --}}
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
        // caso o texto já caiba inteiro sem scroll
        checkScroll();
    });
</script>
</body>
</html>
