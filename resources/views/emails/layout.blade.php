@php
  $appName = config('app.name', 'Tech Market Brasil');
@endphp
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ $subject ?? $appName }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:system-ui,-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;color:#111827;">
  <div style="padding:24px 12px;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:14px;box-shadow:0 10px 30px rgba(0,0,0,.06);overflow:hidden;">
      <div style="padding:18px 20px;background:#111827;color:#ffffff;">
        <div style="font-size:16px;font-weight:700;letter-spacing:.2px;">{{ $appName }}</div>
        @isset($preheader)
          <div style="font-size:12px;opacity:.85;margin-top:6px;">{{ $preheader }}</div>
        @endisset
      </div>

      <div style="padding:20px;">
        @yield('content')
      </div>

      @include('emails.partials.footer')
    </div>

    <div style="max-width:640px;margin:10px auto 0 auto;font-size:12px;color:#6b7280;text-align:center;line-height:1.4;">
      Esta é uma mensagem automática. Se você não reconhece este pedido, responda este e-mail.
    </div>
  </div>
</body>
</html>
