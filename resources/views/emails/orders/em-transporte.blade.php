@extends('emails.layout')

@php
  $subject = "Pedido em transporte #".($order->external_id ?? $order->id);
  $preheader = "Seu pedido já foi enviado e está em transporte.";
@endphp

@section('content')
  <div style="font-size:18px;font-weight:900;color:#111827;margin-bottom:8px;">
    Pedido em transporte 🚚
  </div>

  <div style="font-size:14px;color:#374151;line-height:1.6;">
    Olá, <strong>{{ $customer->name ?? 'Cliente' }}</strong>!<br>
    Seu pedido foi enviado e já está em transporte.
  </div>

  @include('emails.partials.order-summary', ['order' => $order])

  <div style="margin:14px 0;border:1px solid #e5e7eb;border-radius:12px;padding:12px;background:#ffffff;">
    <div style="font-size:12px;color:#6b7280;">Código de rastreio</div>
    <div style="font-size:16px;font-weight:900;color:#111827;">
      {{ $trackingCode ?? $order->tracking_code ?? '-' }}
    </div>
  </div>

  <div style="font-size:13px;color:#6b7280;line-height:1.6;">
    Assim que houver novas movimentações, acompanhe pelo rastreio. Caso precise, responda este e-mail.
  </div>
@endsection
