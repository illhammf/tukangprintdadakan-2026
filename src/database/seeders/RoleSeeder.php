<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'super_admin']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'pemilik_usaha']);
        Role::firstOrCreate(['name' => 'pelanggan']);

        // Opsional: tetap dipertahankan kalau boilerplate masih memakai role "user"
        Role::firstOrCreate(['name' => 'user']);
    }
}