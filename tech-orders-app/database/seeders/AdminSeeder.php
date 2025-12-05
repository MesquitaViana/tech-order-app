<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@techorders.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'), // senha que vamos usar
            ],
        );
    }
}
