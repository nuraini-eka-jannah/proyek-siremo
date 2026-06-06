<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; 

    // Berdasarkan gambar, primary key kamu adalah id_user
    //protected $primaryKey = 'id_user'; 

    public $timestamps = false;
    
    public function username()
    {
        return 'username';
    }

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'no_telepon',
        'cabang_rental',
        'alamat',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}