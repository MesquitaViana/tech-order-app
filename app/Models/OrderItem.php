<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'item_id_gateway',
        'name',
        'price',
        'quantity',
        'description',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
