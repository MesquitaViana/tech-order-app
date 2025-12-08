<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingHistory extends Model
{
    protected $fillable = [
        'order_id',
        'admin_id',
        'old_code',
        'new_code',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
