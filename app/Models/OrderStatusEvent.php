<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusEvent extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'source',
        'comment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
