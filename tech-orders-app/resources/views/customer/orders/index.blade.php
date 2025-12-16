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

        /* Assinaturas */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 14px;
            color: #111827;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }
        .table th, .table td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        .helper {
            font-size: 13px;
            color: #4b5563;
            margin-top: 10px;
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
