<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mobil', function (Blueprint $table) {
            $table->id('id_mobil');
            $table->string('merek');
            $table->string('model');
            $table->string('plat_nomor')->unique();
            $table->year('tahun');
            $table->string('warna');
            $table->unsignedBigInteger('tarif_sewa_per_hari');
            $table->enum('status_ketersediaan', ['Tersedia', 'Disewa', 'Perawatan'])->default('Tersedia');
            $table->string('foto')->nullable();
            $table->string('kategori')->nullable(); // keluarga, City Car, Bus/MiniBus
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mobil');
    }
};