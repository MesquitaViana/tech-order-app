@extends('emails.layout')

@php
  $subject = "Pedido criado #".($order->external_id ?? $order->id);
  $preheader = "Recebemos seu pedido. Em breve você terá novas atualizações.";
@endphp

@section('content')
  <div style="font-size:18px;font-weight:900;color:#111827;margin-bottom:8px;">
    Pedido criado ✅
  </div>

  <div style="font-size:14px;color:#374151;line-height:1.6;">
    Olá, <strong>{{ $customer->name ?? 'Cliente' }}</strong>!<br>
    Recebemos seu pedido e ele já foi registrado no sistema.
  </div>

  @include('emails.partials.order-summary', ['order' => $order])

  <div style="font-size:13px;color:#6b7280;line-height:1.6;">
    Você receberá atualizações por e-mail conforme o status do pedido avançar.
  </div>
@endsection
