<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('type'); // ex: pedido_criado, pago_aprovado, etc
            $table->string('fingerprint')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'type', 'fingerprint']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_email_logs');
    }
};
