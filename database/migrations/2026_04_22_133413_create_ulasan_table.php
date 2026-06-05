<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id('id_ulasan');
            $table->foreignId('id_mobil')
                  ->constrained('mobil', 'id_mobil')
                  ->cascadeOnDelete();
            $table->foreignId('id_penyewa')
                  ->constrained('penyewa', 'id_penyewa')
                  ->cascadeOnDelete();
            $table->foreignId('id_transaksi')
                  ->constrained('transaksi_sewa', 'id_transaksi')
                  ->cascadeOnDelete();
            $table->string('nama')->nullable();
            $table->text('ulasan')->nullable();
            $table->unsignedTinyInteger('rating')->default(5); // 1–5
            $table->timestamp('tanggal')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};