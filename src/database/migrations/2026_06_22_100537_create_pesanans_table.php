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
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kode_pesanan')->unique();
            $table->string('nama_pelanggan');
            $table->string('email')->nullable();
            $table->string('nomor_whatsapp')->nullable();
            $table->date('tanggal_pesan');
            $table->date('tanggal_pengambilan')->nullable();
            $table->time('jam_pengambilan')->nullable();
            $table->string('lokasi_pengambilan')->nullable();
            $table->text('detail_lokasi')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('biaya_tambahan', 12, 2)->default(0);
            $table->decimal('biaya_pengiriman', 12, 2)->default(0);
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->enum('status_pesanan', [
                'menunggu_verifikasi',
                'diproses',
                'siap_diambil',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_verifikasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
