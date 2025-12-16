<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('failed_webhooks', function (Blueprint $table) {
            $table->id();
            $table->string('source')->default('luna');
            $table->longText('payload');
            $table->text('error');
            $table->unsignedInteger('attempts')->default(1);
            $table->boolean('reprocessed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('failed_webhooks');
    }
};
