<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('admin_id')->nullable();

            $table->string('old_code')->nullable();
            $table->string('new_code')->nullable();

            $table->timestamps();

            $table->foreign('order_id')
                  ->references('id')
                  ->on('orders')
                  ->onDelete('cascade');

            $table->foreign('admin_id')
                  ->references('id')
                  ->on('admins')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_histories');
    }
};
