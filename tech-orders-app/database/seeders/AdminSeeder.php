<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@techorders.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('senha-super-forte-123'),
            ],
        );
    }
}
