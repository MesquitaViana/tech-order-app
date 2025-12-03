<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedido #{{ $order->id }} - Painel Tech Orders</title>
    <style>
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
        h1 { margin-bottom: 10px; }
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .card { background: #fff; padding: 16px; border-radius: 8px; margin-bottom: 16px; }
        .label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px; }
        .value { font-size: 14px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { text-align: left; background: #fafafa; }
        .status { font-size: 12px; padding: 3px 8px; border-radius: 999px; background: #e5e7eb; display: inline-block; }
        .gateway { font-size: 12px; }
        pre { background: #111827; color: #e5e7eb; padding: 12px; border-radius: 6px; font-size: 12px; overflow-x: auto; }
        .back { margin-bottom: 16px; display: inline-block; }
    </style>
</head>
<body>
    <a href="{{ route('admin.orders.index') }}" class="back">&larr; Voltar para pedidos</a>

    <h1>Pedido #{{ $order->id }}</h1>
    <p>ID externo (Luna): <strong>{{ $order->external_id }}</strong></p>

    <div class="card">
        <div class="label">Cliente</div>
        <div class="value">
            {{ $order->customer->name ?? '-' }}<br>
            {{ $order->customer->email ?? '-' }}<br>
            @if($order->customer->phone)
                Tel: {{ $order->customer->phone }}
            @endif
        </div>
    </div>

    <div class="card">
        <div class="label">Status</div>
        <div class="value">
            Status interno: <span class="status">{{ $order->status }}</span><br>
            Status gateway: <span class="gateway">{{ $order->gateway_status }}</span><br>
            Método: {{ strtoupper($order->method ?? '-') }}<br>
            Valor: <strong>R$ {{ number_format($order->amount, 2, ',', '.') }}</strong><br>
            Criado em: {{ $order->created_at?->format('d/m/Y H:i') }}
        </div>
    </div>

    <!-- 🔥 ALTERAR STATUS INTERNO -->
    <div class="card">
        <div class="label">Alterar status interno</div>
        <div class="value">

            @if(session('status_message'))
                <p style="color: green; margin-bottom: 8px;">
                    {{ session('status_message') }}
                </p>
            @endif

            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                @csrf

                <label for="status">Novo status:</label><br>
                <select name="status" id="status" style="margin: 6px 0; padding: 4px 8px;">
                    @php
                        $options = ['novo', 'em_separacao', 'enviado', 'entregue', 'cancelado'];
                    @endphp
                    @foreach($options as $opt)
                        <option value="{{ $opt }}" @if($order->status === $opt) selected @endif>
                            {{ strtoupper(str_replace('_', ' ', $opt)) }}
                        </option>
                    @endforeach
                </select>

                <br>
                <label for="comment">Comentário (opcional):</label><br>
                <input
                    type="text"
                    id="comment"
                    name="comment"
                    style="width: 100%; max-width: 400px; padding: 4px 8px; margin: 4px 0;"
                    placeholder="Ex: Pedido separado, aguardando envio..."
                >

                <br>
                <button type="submit" style="margin-top: 8px; padding: 6px 12px; cursor: pointer;">
                    Salvar status
                </button>
            </form>
        </div>
    </div>
    <!-- 🔥 FIM ALTERAR STATUS -->

    <!-- 🔥 HISTÓRICO DE STATUS -->
    <div class="card">
        <div class="label">Histórico de status</div>

        @if($order->statusEvents->count())
            <ul>
                @foreach ($order->statusEvents as $event)
                    <li>
                        [{{ $event->created_at?->format('d/m/Y H:i') }}]
                        <strong>{{ strtoupper(str_replace('_', ' ', $event->status)) }}</strong>
                        — {{ $event->comment ?? 'Sem comentário' }}
                        ({{ $event->source }})
                    </li>
                @endforeach
            </ul>
        @else
            <div class="value">Nenhum evento registrado.</div>
        @endif
    </div>
    <!-- 🔥 FIM HISTÓRICO -->

    <div class="card">
        <div class="label">Itens</div>
        <table>
            <thead>
                <tr>
                    <th>Item (Luna ID)</th>
                    <th>Nome</th>
                    <th>Qtd</th>
                    <th>Preço</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($order->items as $item)
                <tr>
                    <td>{{ $item->item_id_gateway }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Nenhum item registrado para este pedido.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="label">Payload bruto da Luna</div>
        <pre>{{ json_encode($order->raw_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>
</body>
</html>
