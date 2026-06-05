<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    protected $table = 'mobil';
    protected $primaryKey = 'id_mobil';

    protected $fillable = [
        'merek',
        'model',
        'plat_nomor',
        'tahun',
        'warna',
        'tarif_sewa_per_hari',
        'status_ketersediaan', // Tersedia | Disewa | Perawatan
        'foto',
        'kategori',
        'deskripsi',
    ];

    public function transaksiSewa()
    {
        return $this->hasMany(TransaksiSewa::class, 'id_mobil', 'id_mobil');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_mobil', 'id_mobil');
    }

    public function getNamaLengkapAttribute(): string
    {
        return "{$this->merek} {$this->model}";
    }
}