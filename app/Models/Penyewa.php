<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model
{
    use HasFactory;

    protected $table = 'penyewa';
    protected $primaryKey = 'id_penyewa';

    protected $fillable = [
        'nama',
        'alamat',
        'no_ktp',
        'no_sim',
        'foto_sim',
        'no_telepon',
        'email',
        'id_user',
        'tgl_gabung',
    ];

    public function transaksiSewa()
    {
        return $this->hasMany(TransaksiSewa::class, 'id_penyewa', 'id_penyewa');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'id_penyewa', 'id_penyewa');
    }
}