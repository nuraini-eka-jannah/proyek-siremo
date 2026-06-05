<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_sewa', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->foreignId('id_mobil')->constrained('mobil', 'id_mobil')->cascadeOnDelete();
            $table->foreignId('id_penyewa')->constrained('penyewa', 'id_penyewa')->cascadeOnDelete();
            $table->date('tgl_sewa');
            $table->date('tgl_rencana_kembali');
            $table->date('tgl_aktual_kembali')->nullable();
            $table->unsignedInteger('lama_sewa_hari')->default(0);
            $table->unsignedBigInteger('total_bayar')->default(0);
            $table->unsignedBigInteger('denda')->default(0);
            $table->string('ulasan_denda')->nullable();
            $table->enum('status_transaksi', ['Aktif', 'Selesai', 'Batal', 'Disewa'])->default('Aktif');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_sewa');
    }
};