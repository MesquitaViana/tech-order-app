<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Assinatura - Painel Admin | Tech Orders</title>
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
        .back-link:hover { text-decoration: underline; }

        .actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .logout-link {
            font-size: 13px;
            color: #ef4444;
            text-decoration: none;
            font-weight: 600;
        }
        .logout-link:hover { text-decoration: underline; }

        .card {
            background: #f9fafb;
            border-radius: 10px;
            padding: 16px;
        }
        .card-title {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #374151;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .grid-full { grid-column: 1 / -1; }
        label {
            display: block;
            font-size: 12px;
            color: #374151;
            margin-bottom: 6px;
            font-weight: 600;
        }
        input, select, textarea {
            width: 100%;
            padding: 9px 10px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 13px;
            background: #ffffff;
        }
        textarea { resize: vertical; }
        .error-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 10px;
            padding: 12px;
            margin-bottom: 12px;
            font-size: 13px;
        }
        .btn {
            border: none;
            background: #111827;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 14px;
            border-radius: 999px;
            cursor: pointer;
        }
        .btn:hover { background: #0b1220; }
        .muted {
            font-size: 12px;
            color: #6b7280;
            margin-top: 6px;
        }
        .customer-box {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
        }
        .customer-name { font-weight: 700; color: #111827; }
        .customer-email { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .footer-actions {
            margin-top: 14px;
            display: flex;
            justify-content: flex-end;
        }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div>
            <h1 class="header-title">Criar assinatura</h1>
            <div class="header-subtitle">Tech Market Brasil · Admin</div>
        </div>

        <div class="actions">
            <a class="back-link" href="{{ route('admin.subscriptions.index') }}">← Voltar</a>

            <a class="logout-link"
               href="{{ route('admin.logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Sair
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

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

    <div class="card">
        <div class="card-title">Dados da assinatura</div>

        <form method="POST" action="{{ route('admin.subscriptions.store') }}">
            @csrf

            <div class="grid">
                <div class="grid-full">
                    <label>Cliente</label>

                    @if(!empty($customer))
                        <div class="customer-box">
                            <div class="customer-name">{{ $customer->name }}</div>
                            <div class="customer-email">{{ $customer->email }}</div>
                        </div>
                        <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                    @else
                        <input type="number" name="customer_id" value="{{ old('customer_id') }}" placeholder="ID do cliente (ex: 1)">
                        <div class="muted">Dica: normalmente você chega aqui pelo pedido, com o cliente já preenchido.</div>
                    @endif
                </div>

                <div>
                    <label>Produto</label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" placeholder="Ex: Life Pod Eco 2 – 10k">
                </div>

                <div>
                    <label>Sabor (opcional)</label>
                    <input type="text" name="flavor" value="{{ old('flavor') }}" placeholder="Ex: Mint / Ice / Uva...">
                </div>

                <div>
                    <label>Quantidade</label>
                    <input type="number" min="1" name="quantity" value="{{ old('quantity', 1) }}">
                </div>

                <div>
                    <label>Frequência</label>
                    @php($freq = old('frequency', 'mensal'))
                    <select name="frequency">
                        <option value="semanal" @selected($freq==='semanal')>Semanal</option>
                        <option value="quinzenal" @selected($freq==='quinzenal')>Quinzenal</option>
                        <option value="mensal" @selected($freq==='mensal')>Mensal</option>
                        <option value="bimestral" @selected($freq==='bimestral')>Bimestral</option>
                    </select>
                </div>

                <div>
                    <label>Status</label>
                    @php($status = old('status', 'ativa'))
                    <select name="status">
                        <option value="ativa" @selected($status==='ativa')>Ativa</option>
                        <option value="pausada" @selected($status==='pausada')>Pausada</option>
                        <option value="cancelada" @selected($status==='cancelada')>Cancelada</option>
                    </select>
                </div>

                <div>
                    <label>Próxima entrega</label>
                    <input type="date" name="next_delivery_date" value="{{ old('next_delivery_date') }}">
                </div>

                <div class="grid-full">
                    <label>Notas (opcional)</label>
                    <textarea name="notes" rows="4" placeholder="Observações internas...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="footer-actions">
                <button class="btn" type="submit">Salvar assinatura</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
