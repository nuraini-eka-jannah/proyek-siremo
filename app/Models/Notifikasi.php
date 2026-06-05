<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table      = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'judul',
        'pesan',
        'tipe',      // pesanan_sewa | pembayaran | pengembalian | sistem
        'icon',      // nama icon bootstrap-icons
        'warna',     // hex color untuk dot/icon
        'url',       // link tujuan saat notif diklik
        'dibaca',
        'id_transaksi',
    ];

    protected $casts = [
        'dibaca'     => 'boolean',
        'created_at' => 'datetime',
    ];

    // Relasi ke transaksi (opsional)
    public function transaksi()
    {
        return $this->belongsTo(TransaksiSewa::class, 'id_transaksi', 'id_transaksi');
    }

    // Helper: warna default per tipe
    public static function warnaTipe(string $tipe): string
    {
        return match($tipe) {
            'pesanan_sewa'  => '#E8622A',
            'pembayaran'    => '#2DCE89',
            'pengembalian'  => '#1A6FE8',
            default         => '#7A7A7A',
        };
    }

    // Helper: icon default per tipe
    public static function iconTipe(string $tipe): string
    {
        return match($tipe) {
            'pesanan_sewa'  => 'bi-car-front-fill',
            'pembayaran'    => 'bi-credit-card-fill',
            'pengembalian'  => 'bi-arrow-return-left',
            default         => 'bi-bell-fill',
        };
    }
}