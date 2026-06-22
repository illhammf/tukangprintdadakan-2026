<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengirimen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
            $table->enum('metode_pengiriman', ['ambil_di_kampus', 'antar', 'ojek_online'])
                ->default('ambil_di_kampus');
            $table->text('alamat_pengiriman')->nullable();
            $table->decimal('biaya_pengiriman', 12, 2)->default(0);
            $table->enum('status_pengiriman', ['belum_dikirim', 'diproses', 'dikirim', 'selesai'])
                ->default('belum_dikirim');
            $table->text('catatan_pengiriman')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimen');
    }
};
