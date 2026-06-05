<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Tabel & Primary Key
    |--------------------------------------------------------------------------
    | Tabel   : ulasan
    | PK      : id_ulasan  (non-incrementing default Laravel diganti di sini)
    */

    protected $table      = 'ulasan';
    protected $primaryKey = 'id_ulasan';

    // Laravel tidak menggunakan timestamps (created_at / updated_at)
    // karena tabel hanya memiliki kolom `tanggal`
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'id_mobil',
        'id_penyewa',
        'id_transaksi',
        'nama',
        'ulasan',
        'rating',
        'tanggal',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'rating'  => 'integer',
        'tanggal' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi
    |--------------------------------------------------------------------------
    */

    /**
     * Ulasan dimiliki oleh satu Mobil.
     * Sesuaikan nama class & FK jika berbeda di project kamu.
     */
    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'id_mobil', 'id_mobil');
    }

    /**
     * Ulasan dimiliki oleh satu Penyewa.
     */
    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class, 'id_penyewa', 'id_penyewa');
    }

    /**
     * Ulasan terkait dengan satu Transaksi Sewa.
     * Ganti 'TransaksiSewa' dengan nama model transaksi kamu jika berbeda.
     */
    public function transaksi()
    {
        return $this->belongsTo(TransaksiSewa::class, 'id_transaksi', 'id_transaksi');
    }

    /*
    |--------------------------------------------------------------------------
    | Scope Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Filter berdasarkan rating tertentu.
     *
     * Contoh: Ulasan::rating(5)->get();
     */
    public function scopeRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Cari berdasarkan nama penyewa atau teks ulasan.
     *
     * Contoh: Ulasan::cari('nyaman')->get();
     */
    public function scopeCari($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('ulasan', 'like', "%{$keyword}%")
            ->orWhereHas('penyewa', function($queryPenyewa) use ($keyword) {
                $queryPenyewa->where('nama', 'like', "%{$keyword}%");
            });
        });
    }
}