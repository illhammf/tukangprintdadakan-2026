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
        Schema::create('pengaturan_websites', function (Blueprint $table) {
            $table->id();
            $table->string('nama_website')->default('Tukang Print Dadakan');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('nomor_whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('jam_operasional')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_websites');
    }
};
