<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyewa', function (Blueprint $table) {
            $table->id('id_penyewa');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('no_ktp', 20)->nullable();
            $table->string('no_sim', 20)->nullable();
            $table->string('foto_sim')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('email')->nullable();
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('set null');
            $table->timestamp('tgl_gabung')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyewa');
    }
};