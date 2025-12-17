@extends('emails.layout')

@php
  $subject = "Pagamento aprovado #".($order->external_id ?? $order->id);
  $preheader = "Pagamento confirmado. Vamos dar andamento no seu pedido.";
@endphp

@section('content')
  <div style="font-size:18px;font-weight:900;color:#111827;margin-bottom:8px;">
    Pagamento aprovado 🎉
  </div>

  <div style="font-size:14px;color:#374151;line-height:1.6;">
    Olá, <strong>{{ $customer->name ?? 'Cliente' }}</strong>!<br>
    Seu pagamento foi aprovado e já vamos seguir com o processamento do pedido.
  </div>

  @include('emails.partials.order-summary', ['order' => $order])

  <div style="font-size:13px;color:#6b7280;line-height:1.6;">
    Assim que houver atualização (separação/envio), você será avisado por aqui.
  </div>
@endsection
