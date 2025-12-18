<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Assinatura #{{ $subscription->id }} - Admin | Tech Orders</title>

    <style>
        :root{
            --bg: #f3f4f6;
            --card: #ffffff;
            --muted: #6b7280;
            --text: #111827;
            --border: #e5e7eb;
            --soft: #f9fafb;
            --primary: #111827;
            --primaryHover: #0b1220;
            --dangerBg: #fef2f2;
            --dangerBorder: #fecaca;
            --dangerText: #b91c1c;
        }

        * { box-sizing: border-box; }

        body{
            margin:0;
            background: var(--bg);
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: var(--text);
        }

        .page{
            max-width: 1100px;
            margin: 28px auto;
            padding: 0 14px;
        }

        .shell{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(15,23,42,.10);
            padding: 22px 22px 24px;
        }

        .topbar{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 16px;
            margin-bottom: 18px;
            flex-wrap: wrap;
        }

        .title{
            margin:0;
            font-size: 22px;
            font-weight: 900;
            letter-spacing: -0.2px;
        }

        .subtitle{
            margin-top: 6px;
            font-size: 13px;
            color: var(--muted);
        }

        .actions{
            display:flex;
            gap:10px;
            flex-wrap: wrap;
        }

        .btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 10px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            text-decoration:none;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            cursor: pointer;
        }

        .btn:hover{
            filter: brightness(0.98);
        }

        .btn-primary{
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-primary:hover{
            background: var(--primaryHover);
            border-color: var(--primaryHover);
        }

        .card{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 18px;
            margin-top: 14px;
        }

        .card-soft{
            background: var(--soft);
        }

        .card-label{
            font-size: 12px;
            color: var(--muted);
            font-weight: 900;
            letter-spacing: .06em;
            margin-bottom: 8px;
        }

        .customer-name{
            font-size: 16px;
            font-weight: 900;
            margin: 0;
        }

        .customer-email{
            font-size: 13px;
            color: var(--muted);
            margin-top: 4px;
        }

        .error-box{
            background: var(--dangerBg);
            border: 1px solid var(--dangerBorder);
            color: var(--dangerText);
            border-radius: 14px;
            padding: 12px 14px;
            margin-top: 14px;
            font-size: 13px;
        }

        .form-title{
            font-size: 14px;
            font-weight: 900;
            margin: 0 0 14px;
        }

        .grid{
            display:grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        @media(max-width: 860px){
            .grid{ grid-template-columns: 1fr; }
        }

        label{
            display:block;
            font-size: 12px;
            font-weight: 800;
            color: #374151;
            margin-bottom: 6px;
        }

        input, select, textarea{
            width: 100%;
            padding: 10px 11px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 13px;
            background: #fff;
            outline: none;
        }

        input:focus, select:focus, textarea:focus{
            border-color: #c7d2fe;
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }

        textarea{ resize: vertical; min-height: 92px; }

        .grid-full{ grid-column: 1 / -1; }

        .footer-actions{
            display:flex;
            justify-content:flex-end;
            gap: 10px;
            margin-top: 18px;
        }

        .hint{
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px;
        }
    </style>
</head>

<body>
<div class="page">
    <div class="shell">

        {{-- HEADER --}}
        <div class="topbar">
            <div>
                <h1 class="title">Editar assinatura #{{ $subscription->id }}</h1>
                <div class="subtitle">
                    Cliente: <strong>{{ $customer->name }}</strong> · {{ $customer->email }}
                </div>
            </div>

            <div class="actions">
                {{-- Voltar ao último pedido do cliente (opcional) --}}
                @if(!empty($lastOrder))
                    <a class="btn" href="{{ route('admin.orders.show', $lastOrder->id) }}">
                        ← Voltar ao pedido #{{ $lastOrder->id }}
                    </a>
                @endif

                <a class="btn" href="{{ route('admin.subscriptions.index') }}">
                    Todas as assinaturas
                </a>
            </div>
        </div>

        {{-- ERROS --}}
        @if ($errors->any())
            <div class="error-box">
                <strong>Revise os campos:</strong>
                <ul style="margin:8px 0 0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li style="margin:4px 0;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- CARD CLIENTE --}}
        <div class="card card-soft">
            <div class="card-label">CLIENTE</div>
            <p class="customer-name">{{ $customer->name }}</p>
            <div class="customer-email">{{ $customer->email }}</div>
        </div>

        {{-- FORM --}}
        <div class="card">
            <p class="form-title">Dados da assinatura</p>

            <form method="POST" action="{{ route('admin.subscriptions.update', $subscription->id) }}">
                @csrf

                <div class="grid">
                    <div>
                        <label for="product_name">Produto</label>
                        <input id="product_name" type="text" name="product_name"
                               value="{{ old('product_name', $subscription->product_name) }}"
                               placeholder="Ex: Life Pod Eco 2 – 10k">
                    </div>

                    <div>
                        <label for="flavor">Sabor (opcional)</label>
                        <input id="flavor" type="text" name="flavor"
                               value="{{ old('flavor', $subscription->flavor) }}"
                               placeholder="Ex: Mint / Ice / Uva...">
                    </div>

                    <div>
                        <label for="quantity">Quantidade</label>
                        <input id="quantity" type="number" min="1" name="quantity"
                               value="{{ old('quantity', $subscription->quantity ?? 1) }}">
                        <div class="hint">Quantidade de unidades por ciclo (ex: por mês).</div>
                    </div>

                    <div>
                        <label for="frequency">Frequência</label>
                        @php($freq = old('frequency', $subscription->frequency))
                        <select id="frequency" name="frequency">
                            <option value="semanal"  @selected($freq==='semanal')>Semanal</option>
                            <option value="quinzenal" @selected($freq==='quinzenal')>Quinzenal</option>
                            <option value="mensal"   @selected($freq==='mensal')>Mensal</option>
                            <option value="bimestral" @selected($freq==='bimestral')>Bimestral</option>
                        </select>
                    </div>

                    <div>
                        <label for="status">Status</label>
                        @php($status = old('status', $subscription->status))
                        <select id="status" name="status">
                            <option value="ativa" @selected($status==='ativa')>Ativa</option>
                            <option value="pausada" @selected($status==='pausada')>Pausada</option>
                            <option value="cancelada" @selected($status==='cancelada')>Cancelada</option>
                        </select>
                    </div>

                    <div>
                        <label for="next_delivery_date">Próxima entrega</label>
                        <input id="next_delivery_date" type="date" name="next_delivery_date"
                               value="{{ old('next_delivery_date', optional($subscription->next_delivery_date)->format('Y-m-d')) }}">
                    </div>

                    <div class="grid-full">
                        <label for="notes">Notas (opcional)</label>
                        <textarea id="notes" name="notes" placeholder="Observações internas...">{{ old('notes', $subscription->notes) }}</textarea>
                    </div>
                </div>

                <div class="footer-actions">
                    <a class="btn" href="{{ route('admin.subscriptions.index') }}">
                        Cancelar
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
</body>
</html>
