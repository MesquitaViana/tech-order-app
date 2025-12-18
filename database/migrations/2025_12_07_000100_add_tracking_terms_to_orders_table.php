<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('tracking_terms_accepted_at')->nullable();
            $table->string('tracking_terms_accepted_ip', 45)->nullable();
            $table->string('tracking_terms_version', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_terms_accepted_at',
                'tracking_terms_accepted_ip',
                'tracking_terms_version',
            ]);
        });
    }
};

