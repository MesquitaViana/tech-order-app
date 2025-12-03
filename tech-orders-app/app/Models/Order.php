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
        'amount',
        'method',
        'city',
        'state',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'raw_payload',
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
}
