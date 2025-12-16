<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinaturas - Admin | Tech Orders</title>

    <style>
        :root{
            --bg:#f3f4f6;
            --card:#ffffff;
            --text:#111827;
            --muted:#6b7280;
            --border:#e5e7eb;
            --soft:#f9fafb;
            --primary:#111827;
            --primaryHover:#0b1220;
        }
        *{ box-sizing:border-box; }

        body{
            margin:0;
            background:var(--bg);
            font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
            color:var(--text);
        }

        .page{
            max-width:1100px;
            margin:28px auto;
            padding:0 14px;
        }

        .shell{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:18px;
            box-shadow:0 18px 45px rgba(15,23,42,.10);
            padding:22px 22px 24px;
        }

        .topbar{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:16px;
            flex-wrap:wrap;
            margin-bottom:16px;
        }

        .title{
            margin:0;
            font-size:22px;
            font-weight:900;
            letter-spacing:-0.2px;
        }

        .subtitle{
            margin-top:6px;
            font-size:13px;
            color:var(--muted);
        }

        .actions{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            align-items:center;
        }

        .btn{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:10px 14px;
            border-radius:999px;
            font-size:13px;
            font-weight:800;
            text-decoration:none;
            border:1px solid var(--border);
            background:#fff;
            color:var(--text);
        }
        .btn:hover{ filter:brightness(.98); }

        .btn-primary{
            background:var(--primary);
            border-color:var(--primary);
            color:#fff;
        }
        .btn-primary:hover{
            background:var(--primaryHover);
            border-color:var(--primaryHover);
        }

        .logout{
            font-size:13px;
            font-weight:800;
            color:#ef4444;
            text-decoration:none;
            padding:10px 8px;
        }
        .logout:hover{ text-decoration:underline; }

        .filters{
            margin-top:10px;
            background:var(--soft);
            border:1px solid var(--border);
            border-radius:16px;
            padding:14px;
        }

        .searchRow{
            display:flex;
            gap:10px;
            flex-wrap:wrap;
            align-items:center;
        }

        .input{
            flex:1;
            min-width:260px;
            padding:10px 12px;
            border-radius:12px;
            border:1px solid var(--border);
            font-size:13px;
            outline:none;
            background:#fff;
        }
        .input:focus{
            border-color:#c7d2fe;
            box-shadow:0 0 0 3px rgba(99,102,241,.15);
        }

        .btnSearch{
            border:none;
            background:var(--primary);
            color:#fff;
            font-size:13px;
            font-weight:900;
            padding:10px 14px;
            border-radius:999px;
            cursor:pointer;
        }
        .btnSearch:hover{ background:var(--primaryHover); }

        .hint{
            margin-top:10px;
            font-size:12px;
            color:var(--muted);
        }

        .table{
            margin-top:16px;
            border:1px solid var(--border);
            border-radius:16px;
            overflow:hidden;
            background:#fff;
        }

        .thead, .row{
            display:grid;
            grid-template-columns: 1.5fr 1.3fr .5fr .8fr .8fr .7fr;
            gap:0;
            padding:12px 14px;
            align-items:center;
        }

        .thead{
            background:var(--soft);
            font-size:12px;
            font-weight:900;
            color:var(--text);
        }

        .row{
            border-top:1px solid var(--border);
            font-size:13px;
            text-decoration:none;
            color:inherit;
        }
        .row:hover{
            background:#fafafa;
        }

        .strong{ font-weight:900; }
        .muted{ color:var(--muted); font-size:12px; margin-top:4px; }

        .badge{
            display:inline-flex;
            align-items:center;
            padding:5px 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
            border:1px solid var(--border);
            background:#fff;
        }
        .b-active{ background:#ecfdf5; border-color:#a7f3d0; color:#065f46; }
        .b-paused{ background:#fffbeb; border-color:#fde68a; color:#92400e; }
        .b-canceled{ background:#fef2f2; border-color:#fecaca; color:#b91c1c; }

        .pagination{
            margin-top:14px;
        }

        @media(max-width: 900px){
            .thead{ display:none; }
            .row{ grid-template-columns: 1fr; gap:8px; }
            .row > div{ display:flex; justify-content:space-between; gap:10px; }
            .row > div::before{
                content: attr(data-label);
                color: var(--muted);
                font-weight: 900;
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
