<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'external_id',
        'customer_id',
        'status',
        'gateway_status',

        // valores / pagamento
        'amount',
        'total_amount',        // ✅ novo (se existir no BD)
        'method',
        'payment_method',      // ✅ novo (se existir no BD)

        // endereço antigo (fallback)
        'city',
        'state',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',

        // endereço novo (json)
        'shipping_address',    // ✅ novo (se existir no BD)

        // tracking / entrega
        'tracking_code',
        'raw_payload',
        'tracking_terms_accepted_at',
        'tracking_terms_accepted_ip',
        'tracking_terms_version',
        'delivered_at',
        'delivery_person_name',
        'delivery_proof_pin',
        'delivery_proof_url',
    ];

    protected $casts = [
        'raw_payload'   => 'array',
        'total_amount'  => 'decimal:2', // ✅ novo
    ];

    /**
     * Relacionamento: um pedido pertence a um cliente
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relacionamento: um pedido possui vários itens
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relacionamento: um pedido possui vários eventos de status
     */
    public function statusEvents()
    {
        return $this->hasMany(OrderStatusEvent::class);
    }

    public function trackingHistories()
    {
        return $this->hasMany(TrackingHistory::class);
    }

    // ✅ transforma shipping_address em array
    public function getShippingAddressArrayAttribute(): array
    {
        $raw = $this->shipping_address;

        if (!$raw) return [];

        // às vezes vem com aspas duplicadas, então tentamos 2x
        $decoded = json_decode($raw, true);

        if (is_array($decoded)) return $decoded;

        $decoded2 = json_decode(trim($raw, "\""), true);
        return is_array($decoded2) ? $decoded2 : [];
    }

    // ✅ endereço formatado (uma linha) para telas
    public function getShippingAddressLineAttribute(): string
    {
        $a = $this->shipping_address_array;

        // fallback pros campos antigos se não existir JSON
        $street = $a['street'] ?? $this->street;
        $number = $a['number'] ?? $this->number;
        $neigh  = $a['neighborhood'] ?? $this->neighborhood;
        $city   = $a['city'] ?? $this->city;
        $state  = $a['state'] ?? $this->state;
        $zip    = $a['zipcode'] ?? $this->zipcode;
        $comp   = $a['complement'] ?? $this->complement;

        $parts = array_filter([
            trim(($street ?? '') . ($number ? ", {$number}" : '')),
            $comp ? "Comp.: {$comp}" : null,
            $neigh ? "Bairro: {$neigh}" : null,
            trim(($city ?? '') . ($state ? " - {$state}" : '')),
            $zip ? "CEP: {$zip}" : null,
        ]);

        return implode(' • ', $parts);
    }

    // ✅ valor formatado em BRL (string)
    public function getTotalAmountBrAttribute(): string
    {
        return 'R$ ' . number_format((float)($this->total_amount ?? 0), 2, ',', '.');
    }

    // ✅ método (usa novo e cai no antigo se precisar)
    public function getPaymentMethodLabelAttribute(): string
    {
        return $this->payment_method ?? $this->method ?? '-';
    }
}
