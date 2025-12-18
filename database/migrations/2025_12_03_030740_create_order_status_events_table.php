<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_status_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');

            $table->string('status');                // ex: event_sale_pending, pagamento_aprovado, etc.
            $table->string('source')->default('system'); // system/admin
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_events');
    }
};
