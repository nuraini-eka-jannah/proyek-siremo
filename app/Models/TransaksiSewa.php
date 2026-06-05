<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiSewa extends Model
{
    use HasFactory;

    protected $table = 'transaksi_sewa';
    protected $primaryKey = 'id_transaksi';

    public $timestamps = true;

    protected $fillable = [
        'id_mobil',
        'id_penyewa',
        'tgl_sewa',
        'tgl_rencana_kembali',
        'tgl_aktual_kembali',
        'lama_sewa_hari',
        'total_bayar',
        'denda',
        'ulasan_denda',
        'status_transaksi', // Aktif | Selesai | Batal | Disewa
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tgl_sewa'            => 'date',
        'tgl_rencana_kembali' => 'date',
        'tgl_aktual_kembali'  => 'date',
    ];

    public function mobil()
    {
        return $this->belongsTo(Mobil::class, 'id_mobil', 'id_mobil');
    }

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class, 'id_penyewa', 'id_penyewa');
    }

    public function getTotalKeseluruhanAttribute(): int
    {
        return (int) $this->total_bayar + (int) $this->denda;
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'id_transaksi', 'id_transaksi');
    }
}