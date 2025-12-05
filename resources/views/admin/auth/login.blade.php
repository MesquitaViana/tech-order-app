<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Tech Orders</title>
    <style>
        body {
            font-family: system-ui, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            width: 340px;
            background: white;
            padding: 24px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .title {
            font-size: 20px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 16px;
        }
        form label {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }
        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            margin-bottom: 12px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #111827;
            color: white;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background: #000;
        }
        .error {
            color: #b91c1c;
            font-size: 13px;
            margin-bottom: 10px;
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
