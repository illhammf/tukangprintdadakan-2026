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
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_layanan_id')->constrained('kategori_layanans')->cascadeOnDelete();
            $table->string('nama_layanan');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_dasar', 12, 2)->default(0);
            $table->string('satuan')->default('layanan');
            $table->string('gambar')->nullable();
            $table->boolean('butuh_upload_file')->default(true);
            $table->boolean('bisa_online')->default(true);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
