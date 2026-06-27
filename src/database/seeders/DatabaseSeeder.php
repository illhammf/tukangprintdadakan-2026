<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,

            KategoriLayananSeeder::class,
            LayananSeeder::class,
            PengaturanWebsiteSeeder::class,
            PengaturanBookingSeeder::class,
            HariLiburSeeder::class,
            KontakMasukSeeder::class,

            PesananSeeder::class,
            DetailPesananSeeder::class,
            PembayaranSeeder::class,
            PengirimanSeeder::class,
            RiwayatStatusPesananSeeder::class,
        ]);
    }
}