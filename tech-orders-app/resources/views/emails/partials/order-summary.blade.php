@php
  $orderId = $order->external_id ?? ($order->id ?? '-');
  $total = $order->total_amount ?? $order->amount ?? null;
  $status = $order->status ?? '-';
  $gateway = $order->gateway_status ?? '-';

  // Endereço (no seu print: shipping_address é JSON em string)
  $addr = null;
  if (!empty($order->shipping_address)) {
    $decoded = json_decode($order->shipping_address, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
      $addr = $decoded;
    }
  }

  $addrLine = null;
  if ($addr) {
    $addrLine = trim(
      ($addr['street'] ?? '') .
      (isset($addr['number']) ? ', '.$addr['number'] : '') .
      (isset($addr['complement']) && $addr['complement'] ? ' - '.$addr['complement'] : '')
    );

    $addrCity = trim(($addr['city'] ?? '').' / '.($addr['state'] ?? ''));
    $addrZip = $addr['zipcode'] ?? null;
  }
@endphp

<div style="border:1px solid #e5e7eb;border-radius:12px;padding:14px 14px;margin:14px 0;background:#ffffff;">
  <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
    <div style="min-width:220px;">
      <div style="font-size:12px;color:#6b7280;">Pedido</div>
      <div style="font-size:16px;font-weight:800;color:#111827;">#{{ $orderId }}</div>
    </div>

    <div style="min-width:220px;">
      <div style="font-size:12px;color:#6b7280;">Status</div>
      <div style="font-size:14px;font-weight:700;color:#111827;">
        {{ $status }} <span style="color:#6b7280;font-weight:600;">({{ $gateway }})</span>
      </div>
    </div>

    <div style="min-width:220px;">
      <div style="font-size:12px;color:#6b7280;">Total</div>
      <div style="font-size:14px;font-weight:800;color:#111827;">
        @if($total !== null)
          R$ {{ number_format((float)$total, 2, ',', '.') }}
        @else
          -
        @endif
      </div>
    </div>
  </div>

  @if($addrLine)
    <div style="margin-top:12px;border-top:1px dashed #e5e7eb;padding-top:12px;">
      <div style="font-size:12px;color:#6b7280;">Entrega</div>
      <div style="font-size:13px;color:#111827;line-height:1.45;">
        {{ $addrLine }}<br>
        {{ $addrCity }}@if(!empty($addrZip)) — CEP {{ $addrZip }}@endif
      </div>
    </div>
  @endif
</div>
