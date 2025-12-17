@extends('emails.layout')

@php
  $subject = "Acesso à sua conta — Tech Market Brasil";
  $preheader = "Acompanhe seus pedidos e atualizações em um só lugar.";
  $loginUrl = url('/minha-conta'); // ajuste se sua rota for outra
@endphp

@section('content')
  <div style="font-size:18px;font-weight:900;color:#111827;margin-bottom:8px;">
    Acesso à sua conta 👤
  </div>

  <div style="font-size:14px;color:#374151;line-height:1.6;">
    Olá, <strong>{{ $customer->name ?? 'Cliente' }}</strong>!<br>
    Para acompanhar seus pedidos, acesse sua conta usando <strong>o mesmo e-mail</strong> e seu <strong>CPF</strong> (como foi informado no checkout).
  </div>

  @include('emails.partials.button', ['url' => $loginUrl, 'label' => 'Acessar Minha Conta'])

  <div style="font-size:13px;color:#6b7280;line-height:1.6;">
    Dica: se você acabou de comprar, pode levar alguns minutos para o pedido aparecer no painel.
  </div>
@endsection
