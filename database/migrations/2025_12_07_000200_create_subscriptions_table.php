<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id');

            $table->string('product_name');
            $table->string('flavor')->nullable();
            $table->unsignedInteger('quantity')->default(1);

            // ex.: "mensal", "quinzenal", "semanal"
            $table->string('frequency')->nullable();

            // "ativa", "pausada", "cancelada"
            $table->string('status')->default('ativa');

            $table->date('next_delivery_date')->nullable();

            // anotações internas (ex.: combinações específicas, observações do cliente)
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
