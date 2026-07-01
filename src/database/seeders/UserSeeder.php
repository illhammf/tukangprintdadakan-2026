<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'nomor_whatsapp' => '081234567890',
                'password' => Hash::make('password'),
            ]
        );

        $admin->syncRoles(['super_admin']);

        $pelanggan = User::updateOrCreate(
            ['email' => 'user@admin.com'],
            [
                'name' => 'Pelanggan Contoh',
                'nomor_whatsapp' => '081234567891',
                'password' => Hash::make('password'),
            ]
        );

        $pelanggan->syncRoles(['pelanggan']);
    }
}