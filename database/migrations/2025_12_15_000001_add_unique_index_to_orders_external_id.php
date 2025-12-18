<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // SQLite não tem "IF NOT EXISTS" para índices via Schema Builder.
        // Então fazemos um check pelo sqlite_master.
        if (DB::getDriverName() === 'sqlite') {
            $exists = DB::selectOne("
                SELECT 1
                FROM sqlite_master
                WHERE type = 'index'
                  AND name = 'orders_external_id_unique'
                LIMIT 1
            ");

            if ($exists) {
                // índice já existe, não faz nada
                return;
            }
        }

        Schema::table('orders', function (Blueprint $table) {
            $table->unique('external_id', 'orders_external_id_unique');
        });
    }

    public function down(): void
    {
        // down também deve ser resiliente
        try {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropUnique('orders_external_id_unique');
            });
        } catch (\Throwable $e) {
            // ignora se não existir
        }
    }
};
