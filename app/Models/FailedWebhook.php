<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedWebhook extends Model
{
    protected $fillable = [
        'source',
        'payload',
        'error',
        'attempts',
        'reprocessed',
    ];

    protected $casts = [
        'reprocessed' => 'boolean',
    ];
}
