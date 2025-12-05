<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $order->id }} - Painel Admin | Tech Orders</title>
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
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            padding: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
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
        .back-link {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .logout-form {
            margin: 0;
        }
        .logout-button {
            border: none;
            background: #ef4444;
            color: #ffffff;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 999px;
            cursor: pointer;
        }
        .logout-button:hover {
            background: #b91c1c;
        }
        .grid {
            display: grid;
            grid-template-columns: 2fr 1.3fr;
            gap: 16px;
        }
        .card {
            background: #f9fafb;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 12px;
        }
        .card-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }
        .label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .value {
            font-size: 13px;
            color: #111827;
            margin-bottom: 4px;
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
            background: #f3f4f6;
            font-weight: 600;
            color: #4b5563;
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
        .status-success {
            color: #16a34a;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .status-error {
            color: #b91c1c;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .status-form select,
        .status-form textarea {
            width: 100%;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .status-form button {
            padding: 8px 12px;
            border-radius: 8px;
            border: none;
            background: #111827;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }
        .status-form button:hover {
            background: #000000;
        }
        .quick-buttons {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            flex-wrap: wrap;
        }
        .quick-buttons form {
            margin: 0;
        }
        .quick-button-enviado,
        .quick-button-entregue {
            padding: 6px 10px;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
        }
        .quick-button-enviado {
            background: #0369a1;
            color: #e0f2fe;
        }
        .quick-button-enviado:hover {
            background: #075985;
        }
        .quick-button-entregue {
            background: #15803d;
            color: #dcfce7;
        }
        .quick-button-entregue:hover {
            background: #166534;
        }
        ul.timeline {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        ul.timeline li {
            font-size: 13px;
            color: #374151;
            margin-bottom: 4px;
        }
        ul.timeline li span {
            color: #6b7280;
            font-size: 12px;
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
            </div>

            <div class="card">
                <div class="card-title">Endereço de entrega</div>
                <div class="value">
                    {{ $order->address_street ?? '' }}
                    {{ $order->address_number ?? '' }}<br>
                    {{ $order->address_neighborhood ?? '' }}<br>
                    {{ $order->address_city ?? '' }} - {{ $order->address_state ?? '' }}<br>
                    CEP: {{ $order->address_zipcode ?? '' }}
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-title">Status do pedido</div>

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
