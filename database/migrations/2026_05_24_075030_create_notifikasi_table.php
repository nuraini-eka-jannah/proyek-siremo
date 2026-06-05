<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->string('judul');
            $table->text('pesan')->nullable();
            $table->enum('tipe', ['pesanan_sewa', 'pembayaran', 'pengembalian', 'sistem'])
                  ->default('sistem');
            $table->string('icon')->default('bi-bell-fill');
            $table->string('warna', 10)->default('#E8622A');
            $table->string('url')->nullable();
            $table->boolean('dibaca')->default(false);
            $table->unsignedBigInteger('id_transaksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};