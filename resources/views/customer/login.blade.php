<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minha Conta - Tech Market Brasil</title>
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

    * {
        box-sizing: border-box;
    }

    body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        background: linear-gradient(135deg, var(--tech-navy), #020617);
        margin: 0;
        padding: 0;
        color: #111827;
    }

    .wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }

    .card {
        background: var(--white);
        border-radius: 22px;
        box-shadow: 0 30px 80px rgba(7,12,43,0.55);
        max-width: 420px;
        width: 100%;
        padding: 28px 28px 24px;
    }

    /* LOGO */
    .logo {
        font-weight: 900;
        font-size: 18px;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--tech-navy);
        margin-bottom: 8px;
    }

    .logo span {
        color: var(--tech-green);
    }

    h1 {
        margin: 0 0 10px;
        font-size: 22px;
        font-weight: 800;
        color: var(--tech-navy);
    }

    .subtitle {
        font-size: 14px;
        color: var(--tech-gray);
        margin-bottom: 18px;
        line-height: 1.5;
    }

    /* FORM */
    label {
        display: block;
        margin-bottom: 4px;
        font-size: 13px;
        font-weight: 700;
        color: #374151;
    }

    input {
        width: 100%;
        padding: 11px 12px;
        margin-bottom: 12px;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        font-size: 14px;
        outline: none;
        background: #ffffff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }

    input:focus {
        border-color: var(--tech-navy);
        box-shadow: 0 0 0 2px rgba(7,12,43,.12);
    }

    button {
        width: 100%;
        padding: 12px;
        border-radius: 999px;
        border: none;
        background: var(--tech-navy);
        color: #ffffff;
        font-size: 15px;
        cursor: pointer;
        font-weight: 800;
        margin-top: 6px;
        transition: background .15s ease, transform .1s ease;
    }

    button:hover {
        background: #000000;
        transform: translateY(-1px);
    }

    /* ALERTAS */
    .error {
        color: #b91c1c;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .alert {
        background: rgba(239,68,68,.1);
        color: #b91c1c;
        padding: 10px 12px;
        border-radius: 10px;
        margin-bottom: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .helper {
        font-size: 12px;
        color: #6b7280;
        margin-top: -4px;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .footnote {
        margin-top: 14px;
        font-size: 12px;
        color: #9ca3af;
        text-align: center;
        line-height: 1.4;
    }

    @media (max-width: 480px) {
        .card {
            padding: 22px;
        }
    }
</style>

</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="logo">Tech<span>Market</span>BRASIL</div>
        <h1>Entrar na sua conta</h1>
        <p class="subtitle">
            Consulte seus pedidos realizados na <strong>Tech Market Brasil</strong>
            usando o mesmo e-mail e CPF que você informou no checkout.
        </p>

        @if ($errors->any())
            <div class="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('customer.login.post') }}">
            @csrf

            <label for="email">E-mail</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="seuemail@exemplo.com"
                required
            >

            <label for="cpf">CPF</label>
            <input
                type="text"
                id="cpf"
                name="cpf"
                value="{{ old('cpf') }}"
                placeholder="Somente números"
                required
            >
            <div class="helper">
                Use o mesmo CPF do momento da compra.
                Nós usamos o número apenas para localizar seus pedidos
                e ele é armazenado em formato protegido.
            </div>

            <button type="submit">Entrar</button>
        </form>

        <div class="footnote">
            Dúvidas? Fale com nosso suporte pelo WhatsApp da Tech Market Brasil.
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var cpfInput = document.getElementById('cpf');
        if (!cpfInput) return;

        // teclado numérico em mobile
        cpfInput.setAttribute('inputmode', 'numeric');

        cpfInput.addEventListener('input', function (e) {
            var value = e.target.value || '';

            // remove tudo que não for dígito e limita a 11
            value = value.replace(/\D/g, '').slice(0, 11);

            // monta a máscara XXX.XXX.XXX-XX
            if (value.length > 9) {
                e.target.value =
                    value.slice(0, 3) + '.' +
                    value.slice(3, 6) + '.' +
                    value.slice(6, 9) + '-' +
                    value.slice(9, 11);
            } else if (value.length > 6) {
                e.target.value =
                    value.slice(0, 3) + '.' +
                    value.slice(3, 6) + '.' +
                    value.slice(6);
            } else if (value.length > 3) {
                e.target.value =
                    value.slice(0, 3) + '.' +
                    value.slice(3);
            } else {
                e.target.value = value;
            }
        });
    });
</script>
</body>
</html>
