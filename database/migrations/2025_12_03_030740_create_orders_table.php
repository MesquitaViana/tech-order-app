<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique(); // id da Luna
            $table->unsignedBigInteger('customer_id');

            $table->string('status')->default('novo');          // status interno
            $table->string('gateway_status')->nullable();       // status Luna (paid, pending, etc.)

            $table->decimal('amount', 10, 2)->default(0);
            $table->string('method')->nullable();               // card, pix, etc.

            // Endereço
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();

            $table->json('raw_payload')->nullable();

            $table->timestamps();

            $table->foreign('customer_id')
                  ->references('id')
                  ->on('customers')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
