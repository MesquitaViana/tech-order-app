@extends('emails.layout')

@php
  $subject = "Aguardando pagamento #".($order->external_id ?? $order->id);
  $preheader = "Seu pedido está aguardando confirmação de pagamento.";
@endphp

@section('content')
  <div style="font-size:18px;font-weight:900;color:#111827;margin-bottom:8px;">
    Aguardando pagamento ⏳
  </div>

  <div style="font-size:14px;color:#374151;line-height:1.6;">
    Olá, <strong>{{ $customer->name ?? 'Cliente' }}</strong>!<br>
    Seu pedido foi criado e está aguardando a confirmação do pagamento.
  </div>

  @include('emails.partials.order-summary', ['order' => $order])

  <div style="font-size:13px;color:#6b7280;line-height:1.6;">
    Se você já pagou, pode levar alguns minutos para a confirmação aparecer.
  </div>
@endsection
