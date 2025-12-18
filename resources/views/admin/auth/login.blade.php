<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Tech Orders</title>
<style>
    :root {
        --tech-navy: #070c2b;
        --tech-navy-soft: #0b123d;
        --tech-green: #8bc34a;
        --tech-gray: #6b7280;
        --tech-border: #e5e7eb;
        --white: #ffffff;
    }

    * {
        box-sizing: border-box;
    }

    body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        background: linear-gradient(135deg, var(--tech-navy), #020617);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        padding: 0;
        color: #111827;
    }

    .box {
        width: 360px;
        background: var(--white);
        padding: 28px 28px 24px;
        border-radius: 18px;
        box-shadow: 0 30px 80px rgba(7,12,43,0.55);
    }

    /* TÍTULO */
    .title {
        font-size: 20px;
        font-weight: 900;
        text-align: center;
        margin-bottom: 6px;
        color: var(--tech-navy);
        letter-spacing: .04em;
    }

    .subtitle {
        text-align: center;
        font-size: 12px;
        color: var(--tech-gray);
        margin-bottom: 18px;
    }

    /* FORM */
    form label {
        display: block;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 4px;
        color: #374151;
    }

    form input {
        width: 100%;
        padding: 11px 12px;
        border: 1px solid #d1d5db;
        border-radius: 12px;
        margin-bottom: 14px;
        font-size: 14px;
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    form input:focus {
        border-color: var(--tech-navy);
        box-shadow: 0 0 0 2px rgba(7,12,43,.12);
    }

    /* BOTÃO */
    button {
        width: 100%;
        padding: 12px;
        background: var(--tech-navy);
        color: #ffffff;
        font-weight: 800;
        border: none;
        border-radius: 999px;
        cursor: pointer;
        font-size: 14px;
        transition: background .15s ease, transform .1s ease;
        margin-top: 4px;
    }

    button:hover {
        background: #000000;
        transform: translateY(-1px);
    }

    /* ERRO */
    .error {
        background: rgba(239,68,68,.1);
        color: #b91c1c;
        font-size: 13px;
        font-weight: 600;
        padding: 10px 12px;
        border-radius: 10px;
        margin-bottom: 12px;
        text-align: center;
    }

    @media (max-width: 420px) {
        .box {
            width: 100%;
            padding: 22px;
        }
    }
</style>

</head>
<body>

<div class="box">
    <div class="title">Login Admin</div>

    @if($errors->any())
        <div class="error" style="color:#b91c1c; margin-bottom:8px;">
            {{ $errors->first() }}
        </div>
    @endif


<form method="POST" action="{{ route('admin.login.post') }}">
    @csrf

    <label>E-mail</label>
    <input type="email" name="email" required autofocus>

    <label>Senha</label>
    <input type="password" name="password" required>

    <button type="submit">Entrar</button>
</form>
</div>

</body>
</html>
