<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    /**
     * Tampilkan semua ulasan beserta relasi mobil & penyewa
     */
    public function index(Request $request)
{
    $query = Ulasan::with(['mobil', 'penyewa', 'transaksi'])
        ->orderBy('tanggal', 'desc');

    // Menggunakan scope rating() yang baru dibuat
    if ($request->filled('rating')) {
        $query->rating($request->rating);
    }

    // Menggunakan scope cari() yang baru dibuat
    if ($request->filled('search')) {
        $query->cari($request->search);
    }

    $ulasan        = $query->paginate(10)->withQueryString();
    $rataRating    = Ulasan::avg('rating');
    $totalUlasan   = Ulasan::count();

    $distribusi = Ulasan::selectRaw('rating, count(*) as jumlah')
        ->groupBy('rating')
        ->pluck('jumlah', 'rating');

    return view('admin.ulasan.index', compact(
        'ulasan', 'rataRating', 'totalUlasan', 'distribusi'
    ));
}

    /**
     * Hapus ulasan
     */
    public function destroy($id)
    {
        Ulasan::findOrFail($id)->delete();

        return redirect()->route('admin.ulasan.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}