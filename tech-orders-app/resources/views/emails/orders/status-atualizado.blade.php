@extends('emails.layout')

@php
  $subject = "Atualização do pedido #".($order->external_id ?? $order->id);
  $preheader = "Seu pedido teve uma atualização de status.";
@endphp

@section('content')
  <div style="font-size:18px;font-weight:900;color:#111827;margin-bottom:8px;">
    Status atualizado 🔄
  </div>

  <div style="font-size:14px;color:#374151;line-height:1.6;">
    Olá, <strong>{{ $customer->name ?? 'Cliente' }}</strong>!<br>
    O status do seu pedido foi atualizado:
  </div>

  <div style="margin:14px 0;border:1px solid #e5e7eb;border-radius:12px;padding:12px;background:#f9fafb;">
    <div style="font-size:13px;color:#6b7280;">Alteração</div>
    <div style="font-size:14px;font-weight:800;color:#111827;">
      {{ $oldStatus ?? '-' }} → {{ $newStatus ?? '-' }}
    </div>
  </div>

  @include('emails.partials.order-summary', ['order' => $order])

  <div style="font-size:13px;color:#6b7280;line-height:1.6;">
    Você continua recebendo atualizações conforme o pedido avança.
  </div>
@endsection
