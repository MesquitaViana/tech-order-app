<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Minha Conta - Tech Market Brasil')</title>
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
            margin: 0 0 4px;
            color: #111827;
        }
        .subtitle {
            font-size: 14px;
            color: #6b7280;
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

        /* Classes genéricas que podemos reutilizar nas telas */
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111827;
        }
        .helper {
            font-size: 13px;
            color: #4b5563;
            margin-top: 10px;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 600;
        }
        .badge-pill {
            border-radius: 999px;
        }
        .badge-soft {
            background: #eff6ff;
            color: #1d4ed8;
        }

        /* Botão generico de CTA */
        .btn-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: none;
            background: #111827;
            color: #f9fafb;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 16px;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }
        .btn-cta:hover {
            background: #000000;
        }
    </style>
</head>
<body>
<div class="page">
    @yield('content')
</div>
</body>
</html>
