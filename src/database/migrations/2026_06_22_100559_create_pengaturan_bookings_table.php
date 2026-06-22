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
        Schema::create('pengaturan_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengaturan')->default('Default Booking');
            $table->boolean('wajib_h_minus_satu')->default(true);
            $table->time('batas_jam_booking')->default('22:00:00');
            $table->boolean('tutup_sabtu')->default(false);
            $table->boolean('tutup_minggu')->default(true);
            $table->boolean('tutup_tanggal_merah')->default(true);
            $table->integer('maksimal_lembar_per_hari')->nullable();
            $table->integer('maksimal_lembar_per_order')->nullable();
            $table->integer('maksimal_jadwal_belajar_per_jam')->nullable();
            $table->integer('minimal_hari_rapihin_tugas')->nullable();
            $table->decimal('biaya_jilid', 12, 2)->default(0);
            $table->decimal('biaya_laminating', 12, 2)->default(0);
            $table->decimal('biaya_prioritas', 12, 2)->default(0);
            $table->boolean('aktifkan_order_prioritas')->default(false);
            $table->boolean('wajib_upload_bukti_online')->default(false);
            $table->decimal('ongkir_kampus', 12, 2)->default(0);
            $table->boolean('lokasi_luar_kampus_perlu_konfirmasi')->default(true);
            $table->boolean('ojek_online_perlu_konfirmasi')->default(true);
            $table->text('catatan_booking')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_bookings');
    }
};
