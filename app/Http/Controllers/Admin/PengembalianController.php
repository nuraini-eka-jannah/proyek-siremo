<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSewa;
use App\Models\Mobil;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengembalianController extends Controller
{
    /**
     * Daftar transaksi yang sedang aktif / belum dikembalikan
     */
    public function index()
    {
        $transaksi = TransaksiSewa::with(['mobil', 'penyewa'])
            ->whereIn('status_transaksi', ['Aktif', 'Disewa'])
            ->orderBy('tgl_rencana_kembali', 'asc')
            ->paginate(10);

        $totalAktif = TransaksiSewa::whereIn('status_transaksi', ['Aktif', 'Disewa'])->count();

        $totalTerlambat = TransaksiSewa::whereIn('status_transaksi', ['Aktif', 'Disewa'])
        ->where('tgl_rencana_kembali', '<', now())
        ->count();

        $selesaiHariIni = TransaksiSewa::where('status_transaksi', 'Selesai')
        ->whereDate('tgl_aktual_kembali', Carbon::today())
        ->count();

    // Kirim SEMUA variabel ke view
        return view('admin.pengembalian.index', compact(
        'transaksi', 
        'totalAktif', 
        'totalTerlambat', 
        'selesaiHariIni'
    ));
}
    /**
     * Form proses pengembalian untuk 1 transaksi
     */
    public function show($id)
    {
        $transaksi = TransaksiSewa::with(['mobil', 'penyewa'])->findOrFail($id);

        // Hitung estimasi denda jika sudah terlambat
        $tglRencana   = Carbon::parse($transaksi->tgl_rencana_kembali);
        $tglHariIni   = Carbon::today();
        $selisihHari  = $tglHariIni->diffInDays($tglRencana, false); // negatif = terlambat
        $estimasiDenda = 0;

        if ($selisihHari < 0) {
            $tarifPerHari  = $transaksi->mobil->tarif_sewa_per_hari ?? 0;
            $estimasiDenda = abs($selisihHari) * $tarifPerHari;
        }

        return view('admin.pengembalian.show', compact('transaksi', 'estimasiDenda', 'selisihHari'));
    }

    /**
     * Proses pengembalian: catat tgl aktual, hitung denda, ubah status
     */
    public function proses(Request $request, $id)
    {
        $transaksi = TransaksiSewa::with('mobil')->findOrFail($id);

        $request->validate([
            'tgl_aktual_kembali' => 'required|date',
            'denda'              => 'nullable|integer|min:0',
            'ulasan_denda'       => 'nullable|string|max:255',
        ]);

        $tglRencana = Carbon::parse($transaksi->tgl_rencana_kembali);
        $tglAktual  = Carbon::parse($request->tgl_aktual_kembali);
        $selisih    = $tglAktual->diffInDays($tglRencana, false); // negatif = terlambat

        // Hitung denda otomatis jika input denda kosong
        $denda = $request->filled('denda')
            ? (int) $request->denda
            : ($selisih < 0
                ? abs($selisih) * ($transaksi->mobil->tarif_sewa_per_hari ?? 0)
                : 0);

        $transaksi->update([
            'tgl_aktual_kembali' => $request->tgl_aktual_kembali,
            'denda'              => $denda,
            'ulasan_denda'       => $request->ulasan_denda,
            'status_transaksi'   => 'Selesai',
        ]);

        // Kembalikan status mobil → Tersedia
        Mobil::where('id_mobil', $transaksi->id_mobil)
            ->update(['status_ketersediaan' => 'Tersedia']);

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian berhasil diproses.');
    }
}