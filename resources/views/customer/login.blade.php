<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minha Conta - Tech Market Brasil</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #0f172a, #020617);
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
            background: #f9fafb;
            border-radius: 18px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.35);
            max-width: 420px;
            width: 100%;
            padding: 24px 24px 20px;
        }
        .logo {
            font-weight: 800;
            font-size: 18px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #020617;
            margin-bottom: 6px;
        }
        .logo span {
            color: #16a34a;
        }
        h1 {
            margin: 0 0 8px;
            font-size: 20px;
            font-weight: 700;
            color: #020617;
        }
        .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 16px;
        }
        label {
            display: block;
            margin-bottom: 4px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }
        input {
            width: 100%;
            padding: 9px 11px;
            margin-bottom: 10px;
            border-radius: 9px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            outline: none;
            background: #ffffff;
        }
        input:focus {
            border-color: #111827;
            box-shadow: 0 0 0 1px #11182711;
        }
        button {
            width: 100%;
            padding: 11px 12px;
            border-radius: 999px;
            border: none;
            background: #111827;
            color: #fff;
            font-size: 14px;
            cursor: pointer;
            font-weight: 600;
            margin-top: 4px;
        }
        button:hover {
            background: #000000;
        }
        .error {
            color: #b91c1c;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .alert {
            background: #fee2e2;
            color: #b91c1c;
            padding: 8px 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 13px;
        }
        .helper {
            font-size: 11px;
            color: #6b7280;
            margin-top: -4px;
            margin-bottom: 8px;
        }
        .footnote {
            margin-top: 10px;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
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
