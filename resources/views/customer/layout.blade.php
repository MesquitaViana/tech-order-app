<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Minha Conta - Tech Market Brasil')</title>
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

    /* CARD PADRÃO */
    .card {
        background: var(--white);
        border-radius: 18px;
        box-shadow: 0 20px 50px rgba(7,12,43,0.12);
        padding: 24px;
        margin-bottom: 22px;
    }

    /* HEADER BASE */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 16px;
    }

    .title {
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 6px;
        color: var(--tech-navy);
    }

    .subtitle {
        font-size: 14px;
        color: var(--tech-gray);
        margin: 0;
        line-height: 1.5;
    }

    /* LOGOUT */
    .logout-button {
        border: none;
        background: var(--tech-navy);
        color: #ffffff;
        font-size: 13px;
        font-weight: 800;
        padding: 8px 16px;
        border-radius: 999px;
        cursor: pointer;
        transition: background .15s ease;
    }

    .logout-button:hover {
        background: #000000;
    }

    /* SEÇÕES GENÉRICAS */
    .section-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--tech-navy);
    }

    .helper {
        font-size: 14px;
        color: #4b5563;
        margin-top: 10px;
        line-height: 1.5;
    }

    /* BADGES */
    .badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .badge-pill {
        border-radius: 999px;
    }

    .badge-soft {
        background: rgba(7,12,43,.08);
        color: var(--tech-navy);
    }

    /* BOTÃO CTA PADRÃO */
    .btn-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        border: none;
        background: var(--tech-navy);
        color: #ffffff;
        font-size: 14px;
        font-weight: 800;
        padding: 10px 20px;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: background .15s ease, transform .1s ease;
    }

    .btn-cta:hover {
        background: #000000;
        transform: translateY(-1px);
    }

    /* MOBILE */
    @media (max-width: 768px) {
        .title {
            font-size: 20px;
        }
        .card {
            padding: 20px;
        }
    }
</style>
</head>
<body>
<div class="page">
    @yield('content')
</div>
</body>
</html>
