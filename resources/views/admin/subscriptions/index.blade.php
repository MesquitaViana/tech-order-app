<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinaturas - Admin | Tech Orders</title>

    <style>
    :root{
        --tech-navy:#070c2b;
        --tech-navy-soft:#0b123d;
        --tech-green:#8bc34a;
        --tech-green-dark:#6ea92f;
        --tech-red:#ef4444;
        --tech-bg:#f4f6fb;
        --card:#ffffff;
        --text:#111827;
        --muted:#6b7280;
        --border:#e5e7eb;
        --soft:#f9fafb;
    }

    *{ box-sizing:border-box; }

    body{
        margin:0;
        background:var(--tech-bg);
        font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
        color:var(--text);
    }

    .page{
        max-width:1100px;
        margin:32px auto;
        padding:0 16px;
    }

    .shell{
        background:var(--card);
        border:1px solid var(--border);
        border-radius:18px;
        box-shadow:0 20px 50px rgba(7,12,43,.12);
        padding:26px 26px 28px;
    }

    /* TOPBAR */
    .topbar{
        display:flex;
        justify-content:space-between;
        align-items:flex-start;
        gap:20px;
        flex-wrap:wrap;
        margin-bottom:18px;
    }

    .title{
        margin:0;
        font-size:22px;
        font-weight:900;
        color:var(--tech-navy);
    }

    .subtitle{
        margin-top:6px;
        font-size:14px;
        color:var(--muted);
    }

    .actions{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        align-items:center;
    }

    .btn{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 16px;
        border-radius:999px;
        font-size:13px;
        font-weight:800;
        text-decoration:none;
        border:1px solid var(--border);
        background:#fff;
        color:var(--tech-navy);
        transition:background .15s ease, transform .1s ease;
    }

    .btn:hover{
        background:#f3f4f6;
        transform:translateY(-1px);
    }

    .btn-primary{
        background:var(--tech-navy);
        border-color:var(--tech-navy);
        color:#fff;
    }

    .btn-primary:hover{
        background:#000;
    }

    .logout{
        font-size:13px;
        font-weight:800;
        color:var(--tech-red);
        text-decoration:none;
        padding:10px 8px;
    }

    .logout:hover{ text-decoration:underline; }

    /* FILTERS */
    .filters{
        margin-top:12px;
        background:var(--soft);
        border:1px solid var(--border);
        border-radius:16px;
        padding:16px;
    }

    .searchRow{
        display:flex;
        gap:12px;
        flex-wrap:wrap;
        align-items:center;
    }

    .input{
        flex:1;
        min-width:260px;
        padding:11px 12px;
        border-radius:12px;
        border:1px solid var(--border);
        font-size:14px;
        outline:none;
        background:#fff;
        transition:border-color .15s ease, box-shadow .15s ease;
    }

    .input:focus{
        border-color:var(--tech-navy);
        box-shadow:0 0 0 2px rgba(7,12,43,.12);
    }

    .btnSearch{
        border:none;
        background:var(--tech-navy);
        color:#fff;
        font-size:14px;
        font-weight:900;
        padding:11px 18px;
        border-radius:999px;
        cursor:pointer;
        transition:background .15s ease, transform .1s ease;
    }

    .btnSearch:hover{
        background:#000;
        transform:translateY(-1px);
    }

    .hint{
        margin-top:10px;
        font-size:12px;
        color:var(--muted);
    }

    /* TABLE */
    .table{
        margin-top:18px;
        border:1px solid var(--border);
        border-radius:16px;
        overflow:hidden;
        background:#fff;
    }

    .thead, .row{
        display:grid;
        grid-template-columns: 1.6fr 1.4fr .6fr .9fr .9fr .8fr;
        padding:14px 16px;
        align-items:center;
    }

    .thead{
        background:var(--soft);
        font-size:12px;
        font-weight:900;
        color:var(--tech-navy);
        text-transform:uppercase;
        letter-spacing:.04em;
    }

    .row{
        border-top:1px solid var(--border);
        font-size:14px;
        text-decoration:none;
        color:inherit;
        transition:background .12s ease;
    }

    .row:hover{
        background:#f5f7ff;
    }

    .strong{
        font-weight:800;
        color:#111827;
    }

    .muted{
        color:var(--muted);
        font-size:12px;
        margin-top:4px;
    }

    /* BADGES */
    .badge{
        display:inline-flex;
        align-items:center;
        padding:6px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        border:1px solid var(--border);
        background:#fff;
        text-transform:uppercase;
        letter-spacing:.04em;
    }

    .b-active{
        background:rgba(139,195,74,.2);
        border-color:rgba(139,195,74,.45);
        color:var(--tech-green-dark);
    }

    .b-paused{
        background:rgba(250,204,21,.25);
        border-color:#fde68a;
        color:#92400e;
    }

    .b-canceled{
        background:rgba(239,68,68,.2);
        border-color:#fecaca;
        color:#b91c1c;
    }

    /* PAGINATION */
    .pagination{
        margin-top:18px;
    }

    /* MOBILE */
    @media(max-width: 900px){
        .thead{ display:none; }

        .row{
            grid-template-columns:1fr;
            gap:10px;
        }

        .row > div{
            display:flex;
            justify-content:space-between;
            gap:10px;
        }

        .row > div::before{
            content: attr(data-label);
            color: var(--muted);
            font-weight: 800;
            font-size: 12px;
            letter-spacing: .04em;
        }
    }
</style>

</head>

<body>
<div class="page">
    <div class="shell">
        <div class="topbar">
            <div>
                <h1 class="title">Assinaturas</h1>
                <div class="subtitle">Gerencie assinaturas dos clientes</div>
            </div>

            <div class="actions">
                {{-- 🔥 botão de voltar pro módulo de pedidos --}}
                <a class="btn" href="{{ route('admin.orders.index') }}">Pedidos</a>

                <a class="btn btn-primary" href="{{ route('admin.subscriptions.create') }}">Nova assinatura</a>

                {{-- Sair (mantenho simples; se seu logout for POST, me fala e eu já ajusto certinho) --}}
                <a class="logout" href="{{ route('admin.logout') }}">Sair</a>
            </div>
        </div>

        <div class="filters">
            <form method="GET" action="{{ route('admin.subscriptions.index') }}">
                <div class="searchRow">
                    <input class="input" type="text" name="q" value="{{ request('q') }}"
                           placeholder="Buscar por cliente (nome/email) ou produto...">
                    <button class="btnSearch" type="submit">Buscar</button>
                </div>
                <div class="hint">
                    Dica: você pode buscar por <strong>nome</strong>, <strong>email</strong> ou <strong>produto</strong>.
                </div>
            </form>
        </div>

        <div class="table">
            <div class="thead">
                <div>Cliente</div>
                <div>Produto</div>
                <div>Qtd</div>
                <div>Frequência</div>
                <div>Próxima</div>
                <div>Status</div>
            </div>

            @forelse($subscriptions as $sub)
                @php
                    $status = $sub->status ?? '';
                    $badgeClass = $status === 'ativa' ? 'b-active' : ($status === 'pausada' ? 'b-paused' : ($status === 'cancelada' ? 'b-canceled' : ''));
                    $next = $sub->next_delivery_date ? \Illuminate\Support\Carbon::parse($sub->next_delivery_date)->format('d/m/Y') : '—';
                @endphp

                <a class="row" href="{{ route('admin.subscriptions.edit', $sub->id) }}">
                    <div data-label="Cliente">
                        <div>
                            <div class="strong">{{ $sub->customer->name ?? '—' }}</div>
                            <div class="muted">{{ $sub->customer->email ?? '' }}</div>
                        </div>
                        <span style="display:none;"></span>
                    </div>

                    <div data-label="Produto">
                        <div>
                            <div class="strong">{{ $sub->product_name }}</div>
                            @if($sub->flavor)
                                <div class="muted">{{ $sub->flavor }}</div>
                            @endif
                        </div>
                        <span style="display:none;"></span>
                    </div>

                    <div data-label="Qtd">
                        <div>{{ $sub->quantity }}</div>
                        <span style="display:none;"></span>
                    </div>

                    <div data-label="Frequência">
                        <div>{{ $sub->frequency }}</div>
                        <span style="display:none;"></span>
                    </div>

                    <div data-label="Próxima">
                        <div>{{ $next }}</div>
                        <span style="display:none;"></span>
                    </div>

                    <div data-label="Status">
                        <div>
                            <span class="badge {{ $badgeClass }}">{{ $sub->status }}</span>
                        </div>
                        <span style="display:none;"></span>
                    </div>
                </a>
            @empty
                <div class="row" style="grid-template-columns: 1fr;">
                    <div style="color:var(--muted);">
                        Nenhuma assinatura encontrada.
                    </div>
                </div>
            @endforelse
        </div>

        <div class="pagination">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
</body>
</html>
