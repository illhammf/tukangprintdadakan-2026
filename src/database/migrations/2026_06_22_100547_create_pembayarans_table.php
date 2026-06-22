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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanans')->cascadeOnDelete();
            $table->enum('metode_pembayaran', ['cash', 'transfer'])->default('cash');
            $table->string('channel_pembayaran')->nullable();
            $table->decimal('jumlah_bayar', 12, 2)->default(0);
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi', 'lunas', 'ditolak'])
                ->default('belum_bayar');
            $table->timestamp('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
