<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Data/hora em que o pedido foi efetivamente entregue
            $table->timestamp('delivered_at')->nullable();

            // Nome do entregador / motoboy
            $table->string('delivery_person_name')->nullable();

            // Prova de entrega: pode ser PIN digitado pelo cliente
            $table->string('delivery_proof_pin')->nullable();

            // URL para foto/assinatura do comprovante (imagem armazenada em outro lugar)
            $table->string('delivery_proof_url')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivered_at',
                'delivery_person_name',
                'delivery_proof_pin',
                'delivery_proof_url',
            ]);
        });
    }
};
