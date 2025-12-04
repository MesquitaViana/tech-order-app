<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Minha Conta - Tech Orders</title>
    <style>
        body { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 400px; margin: 80px auto; background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        h1 { margin-top: 0; margin-bottom: 16px; font-size: 20px; }
        label { display: block; margin-bottom: 4px; font-size: 13px; font-weight: 600; color: #374151; }
        input { width: 100%; padding: 8px 10px; margin-bottom: 12px; border-radius: 6px; border: 1px solid #d1d5db; font-size: 14px; }
        button { width: 100%; padding: 10px 12px; border-radius: 6px; border: none; background: #111827; color: #fff; font-size: 14px; cursor: pointer; font-weight: 600; }
        button:hover { background: #000; }
        .error { color: #b91c1c; font-size: 13px; margin-bottom: 8px; }
        .alert { background: #fee2e2; color: #b91c1c; padding: 8px 10px; border-radius: 6px; margin-bottom: 10px; font-size: 13px; }
        .helper { font-size: 12px; color: #6b7280; margin-top: 4px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Acompanhe seu pedido</h1>
    <p style="font-size: 13px; color: #4b5563;">
        Informe o mesmo <strong>e-mail</strong> e <strong>CPF</strong> usados na compra na Tech Market Brasil.
    </p>

    @if(session('error'))
        <div class="alert">{{ session('error') }}</div>
    @endif

    @if($errors->has('login'))
        <div class="alert">{{ $errors->first('login') }}</div>
    @endif

    <form method="POST" action="{{ route('customer.login.post') }}">
        @csrf

        <label for="email">E-mail</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
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
        <div class="helper">Usamos seu CPF somente para localizar seus pedidos. Não armazenamos o número em texto puro.</div>

        <button type="submit">Entrar</button>
    </form>
</div>
</body>
</html>
