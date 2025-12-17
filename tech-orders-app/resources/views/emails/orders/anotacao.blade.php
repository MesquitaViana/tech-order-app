@extends('emails.layout')

@php
  $subject = "Atualização do seu pedido #".($order->external_id ?? $order->id);
  $preheader = "Há uma nova mensagem sobre seu pedido.";
@endphp

@section('content')
  <div style="font-size:18px;font-weight:900;color:#111827;margin-bottom:8px;">
    Mensagem sobre seu pedido 📝
  </div>

  <div style="font-size:14px;color:#374151;line-height:1.6;">
    Olá, <strong>{{ $customer->name ?? 'Cliente' }}</strong>!<br>
    Registramos uma anotação referente ao seu pedido:
  </div>

  <div style="margin:14px 0;border:1px solid #e5e7eb;border-radius:12px;padding:12px;background:#f9fafb;">
    <div style="font-size:13px;color:#111827;line-height:1.65;white-space:pre-line;">
      {{ $noteText ?? '-' }}
    </div>
  </div>

  @include('emails.partials.order-summary', ['order' => $order])

  <div style="font-size:13px;color:#6b7280;line-height:1.6;">
    Se precisar responder, basta responder este e-mail.
  </div>
@endsection
