<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'customer_id',
        'product_name',
        'flavor',
        'quantity',
        'frequency',
        'status',
        'next_delivery_date',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

