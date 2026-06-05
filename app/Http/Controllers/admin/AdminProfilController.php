<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfilController extends Controller
{
    public function updateProfil(Request $request)
    {
        // 1. Validasi input dari form modal
        $request->validate([
            'no_telepon'    => 'nullable|string|max:15',
            'cabang_rental' => 'nullable|string|max:100',
            'alamat'        => 'nullable|string',
        ]);

        // 2. Ambil data user admin yang sedang login
        $user = Auth::user();
        
        // 3. Update data profil ke database
        // Catatan: Pastikan kolom ini sudah ada di tabel users kamu, 
        // atau jika menggunakan tabel terpisah silakan disesuaikan.
        $user->update([
            'no_telepon'    => $request->no_telepon,
            'cabang_rental' => $request->cabang_rental,
            'alamat'        => $request->alamat,
        ]);

        // 4. Kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Profil admin berhasil diperbarui!');
    }
}