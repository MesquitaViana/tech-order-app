<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    /**
     * Campos permitidos para mass assignment
     */
    protected $fillable = [
        'name',
        'email',
        'cpf_hash',
        'phone',
    ];

    /**
     * Casts úteis
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Pedidos do cliente
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class)->orderByDesc('created_at');
    }

    /**
     * Assinaturas do cliente
     *
     * ⚠️ NÃO filtrar por status aqui.
     * O cliente deve ver todas (ativa, pausada, cancelada).
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->orderByDesc('created_at');
    }
}
