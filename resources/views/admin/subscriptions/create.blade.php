<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Criar Assinatura - Painel Admin | Tech Orders</title>
    <style>
    :root {
        --tech-navy: #070c2b;
        --tech-navy-soft: #0b123d;
        --tech-green: #8bc34a;
        --tech-green-dark: #6ea92f;
        --tech-red: #ef4444;
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
        gap: 18px;
        margin-bottom: 22px;
    }

    .header-title {
        font-size: 22px;
        font-weight: 800;
        margin: 0;
        color: var(--tech-navy);
    }

    .header-subtitle {
        font-size: 14px;
        color: var(--tech-gray);
        margin-top: 6px;
    }

    .actions {
        display: flex;
        gap: 14px;
        align-items: center;
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

    .logout-link {
        font-size: 13px;
        font-weight: 700;
        color: var(--tech-red);
        text-decoration: none;
    }

    .logout-link:hover {
        text-decoration: underline;
    }

    /* CARD */
    .card {
        background: #f9fafb;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid var(--tech-border);
    }

    .card-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 14px;
        color: var(--tech-navy);
    }

    /* GRID */
    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px 16px;
    }

    .grid-full {
        grid-column: 1 / -1;
    }

    /* FORM */
    label {
        display: block;
        font-size: 13px;
        color: #374151;
        margin-bottom: 6px;
        font-weight: 700;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        font-size: 14px;
        background: #ffffff;
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--tech-navy);
        box-shadow: 0 0 0 2px rgba(7,12,43,.12);
    }

    textarea {
        resize: vertical;
    }

    /* CUSTOMER BOX */
    .customer-box {
        background: var(--white);
        border: 1px solid var(--tech-border);
        border-radius: 12px;
        padding: 14px;
    }

    .customer-name {
        font-weight: 800;
        color: #111827;
    }

    .customer-email {
        font-size: 13px;
        color: var(--tech-gray);
        margin-top: 4px;
    }

    /* ERROR */
    .error-box {
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.3);
        color: #b91c1c;
        border-radius: 14px;
        padding: 14px;
        margin-bottom: 18px;
        font-size: 14px;
    }

    /* BUTTON */
    .footer-actions {
        margin-top: 18px;
        display: flex;
        justify-content: flex-end;
    }

    .btn {
        border: none;
        background: var(--tech-navy);
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
        padding: 12px 22px;
        border-radius: 999px;
        cursor: pointer;
        transition: background .15s ease, transform .1s ease;
    }

    .btn:hover {
        background: #000000;
        transform: translateY(-1px);
    }

    /* HELPERS */
    .muted {
        font-size: 13px;
        color: var(--tech-gray);
        margin-top: 6px;
        line-height: 1.4;
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
