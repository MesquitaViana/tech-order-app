<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('gateway_status');
            }

            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->nullable()->after('payment_method');
            }

            if (!Schema::hasColumn('orders', 'shipping_address')) {
                $table->longText('shipping_address')->nullable()->after('total_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->dropColumn('shipping_address');
            }
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->dropColumn('total_amount');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
        });
    }
};
