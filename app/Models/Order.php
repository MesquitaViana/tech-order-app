<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TrackingHistory;


class Order extends Model
{
    protected $fillable = [
        'external_id',
        'customer_id',
        'status',
        'gateway_status',
        'amount',
        'method',
        'city',
        'tracking_code',
        'state',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
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
        'raw_payload' => 'array',
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

}
