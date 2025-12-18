<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Meus pedidos - Tech Market Brasil</title>
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
        border-radius: 16px;
        box-shadow: 0 20px 50px rgba(7,12,43,0.12);
        padding: 24px;
        margin-bottom: 22px;
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
        margin: 0;
        color: var(--tech-navy);
    }

    .subtitle {
        font-size: 14px;
        color: var(--tech-gray);
        margin-top: 6px;
        line-height: 1.4;
    }

    .customer-name {
        font-weight: 700;
        color: var(--tech-navy);
    }

    /* LOGOUT */
    .logout-button {
        border: none;
        background: var(--tech-navy);
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        padding: 8px 16px;
        border-radius: 999px;
        cursor: pointer;
        transition: all .2s ease;
    }

    .logout-button:hover {
        background: #000000;
    }

    /* TABLE */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
    }

    th, td {
        padding: 12px 10px;
        border-bottom: 1px solid var(--tech-border);
        font-size: 14px;
        text-align: left;
    }

    th {
        background: #f9fafb;
        color: var(--tech-navy);
        font-weight: 700;
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
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .badge-status {
        background: rgba(7,12,43,.08);
        color: var(--tech-navy);
    }

    .badge-paid {
        background: rgba(139,195,74,.15);
        color: var(--tech-green-dark);
    }

    .badge-pending {
        background: rgba(234,179,8,.18);
        color: #854d0e;
    }

    .badge-canceled {
        background: rgba(239,68,68,.15);
        color: #b91c1c;
    }

    /* LINKS */
    .link {
        color: var(--tech-navy);
        font-weight: 700;
        text-decoration: none;
    }

    .link:hover {
        text-decoration: underline;
    }

    /* EMPTY STATE */
    .empty {
        font-size: 15px;
        color: var(--tech-gray);
        padding: 22px 0;
    }

    .empty small {
        display: block;
        font-size: 13px;
        color: #9ca3af;
        margin-top: 6px;
    }

    /* SECTION */
    .section-title {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 16px;
        color: var(--tech-navy);
    }

    .helper {
        font-size: 14px;
        color: var(--tech-gray);
        margin-top: 14px;
        line-height: 1.5;
    }

    /* CARD IA */
    .card-ia {
        background: linear-gradient(135deg, var(--tech-navy), var(--tech-navy-soft));
        color: #fff;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 20px 60px rgba(7,12,43,0.35);
    }

    .card-ia h2 {
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 10px;
    }

    .card-ia p {
        font-size: 15px;
        color: #dbeafe;
        margin-bottom: 18px;
        max-width: 680px;
    }

    .card-ia a {
        display: inline-block;
        padding: 12px 22px;
        background: var(--tech-green);
        color: #111827;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 800;
        text-decoration: none;
        transition: all .2s ease;
    }

    .card-ia a:hover {
        background: var(--tech-green-dark);
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
        }
        th, td {
            font-size: 13px;
        }
    }
</style>

</head>
<body>
<div class="page">

    <!-- CARD DE PEDIDOS -->
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
                Não encontramos pedidos para este e-mail/CPF ainda.
                <small>
                    Verifique se você está usando os mesmos dados do checkout.
                    Se o pagamento foi aprovado há poucos minutos, aguarde um pouco e atualize esta página.
                </small>
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
                        $gatewayStatusRaw = strtolower($order->gateway_status ?? '');
                        $gatewayBadgeClass = 'badge-pending';
                        $gatewayLabel = '—';

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
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ optional($order->created_at)->format('d/m/Y H:i') }}</td>

                        {{-- ✅ CORRIGIDO: usa accessor total_amount_br --}}
                        <td>{{ $order->total_amount_br }}</td>

                        <td>
                            <span class="badge badge-status">
                                {{ strtoupper(str_replace('_', ' ', $order->status ?? '—')) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $gatewayBadgeClass }}">
                                {{ $gatewayLabel }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="link">Ver detalhes</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- CARD DE ASSINATURAS -->
    <div class="card" style="margin-top: 24px;">
        <div class="section-title">Minhas assinaturas</div>

        @if(isset($subscriptions) && $subscriptions->count())
            <table class="table">
                <thead>
                <tr>
                    <th>Produto</th>
                    <th>Sabor / variação</th>
                    <th>Quantidade</th>
                    <th>Frequência</th>
                    <th>Status</th>
                    <th>Próxima entrega</th>
                </tr>
                </thead>
                <tbody>
                @foreach($subscriptions as $subscription)
                    @php
                        $statusLabel = match($subscription->status) {
                            'ativa'    => 'Ativa',
                            'pausada'  => 'Pausada',
                            'cancelada'=> 'Cancelada',
                            default    => ucfirst($subscription->status),
                        };
                    @endphp
                    <tr>
                        <td>{{ $subscription->product_name }}</td>
                        <td>{{ $subscription->flavor ?: '—' }}</td>
                        <td>{{ $subscription->quantity }}</td>
                        <td>{{ $subscription->frequency ?: '—' }}</td>
                        <td>{{ $statusLabel }}</td>
                        <td>
                            @if($subscription->next_delivery_date)
                                {{ \Illuminate\Support\Carbon::parse($subscription->next_delivery_date)->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            @if($subscriptions->where('status', 'ativa')->count())
                <p class="helper">
                    Se você precisar alterar frequência, pausar ou cancelar alguma assinatura,
                    entre em contato com nosso time de suporte informando o produto e seu e-mail.
                </p>
            @endif
        @else
            <p class="helper">
                Você ainda não possui nenhuma assinatura ativa registrada em nosso sistema.
                Caso já tenha combinado uma assinatura via WhatsApp, nosso time pode registrá-la
                para você e ela aparecerá aqui.
            </p>
        @endif
    </div>

    <!-- ⭐ CARD DA IA – PERSONALIZAÇÃO DE BOX -->
    <div class="card" style="margin-top: 20px; padding: 24px; border-radius: 14px; background: #ffffff; box-shadow: 0 12px 40px rgba(15,23,42,0.10);">
        <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 8px;">
            Descubra sua Box ideal com DOUG IA 🥶🎁
        </h2>

        <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">
            A inteligência artificial da Tech Market analisa seu perfil de uso e histórico de pedidos
            para recomendar a assinatura perfeita para você — personalizada e automática.
        </p>

        <a href="{{ route('customer.subscriptions.assistant') }}"
           style="
                display: inline-block;
                padding: 10px 18px;
                background: #111827;
                color: white;
                border-radius: 8px;
                font-size: 14px;
                font-weight: 600;
                text-decoration: none;
           ">
            Descobrir minha Box personalizada
        </a>
    </div>

</div>
</body>
</html>
