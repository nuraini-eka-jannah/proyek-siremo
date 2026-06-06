<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSewa;
use App\Models\Mobil;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = TransaksiSewa::with(['mobil', 'penyewa'])
            ->orderBy('id_transaksi', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status_transaksi', $request->status);
        }

        // Filter by pencarian (nama penyewa / plat / id transaksi)
        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('id_transaksi', 'like', "%{$cari}%")
                  ->orWhereHas('penyewa', fn($p) => $p->where('nama', 'like', "%{$cari}%"))
                  ->orWhereHas('mobil',   fn($m) => $m->where('plat_nomor', 'like', "%{$cari}%")
                                                       ->orWhere('model',    'like', "%{$cari}%"));
            });
        }

        $transaksi = $query->paginate(15)->withQueryString();

        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $mobil   = Mobil::where('status_ketersediaan', 'Tersedia')->get();
        $penyewa = Penyewa::orderBy('nama')->get();
        return view('admin.transaksi.create', compact('mobil', 'penyewa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_mobil'            => 'required|exists:mobil,id_mobil',
            'id_penyewa'          => 'required|exists:penyewa,id_penyewa',
            'tgl_sewa'            => 'required|date',
            'tgl_rencana_kembali' => 'required|date|after_or_equal:tgl_sewa',
            'lama_sewa_hari'      => 'required|integer|min:1',
            'total_bayar'         => 'required|integer|min:0',
            'status_transaksi'    => 'required|in:Aktif,Selesai,Batal,Disewa',
            'bukti_pembayaran'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('bukti_pembayaran');
        $data['denda']        = 0;
        $data['ulasan_denda'] = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                ->store('bukti', 'public');
        }

        TransaksiSewa::create($data);

        // Update status mobil → Disewa
        Mobil::where('id_mobil', $request->id_mobil)
            ->update(['status_ketersediaan' => 'Disewa']);

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show($id)
    {
        $transaksi = TransaksiSewa::with(['mobil', 'penyewa'])->findOrFail($id);
        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function edit($id)
    {
        $transaksi = TransaksiSewa::findOrFail($id);
        $mobil     = Mobil::all();
        $penyewa   = Penyewa::orderBy('nama')->get();
        return view('admin.transaksi.edit', compact('transaksi', 'mobil', 'penyewa'));
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiSewa::findOrFail($id);

        $request->validate([
            'id_mobil'            => 'required|exists:mobil,id_mobil',
            'id_penyewa'          => 'required|exists:penyewa,id_penyewa',
            'tgl_sewa'            => 'required|date',
            'tgl_rencana_kembali' => 'required|date|after_or_equal:tgl_sewa',
            'lama_sewa_hari'      => 'required|integer|min:1',
            'total_bayar'         => 'required|integer|min:0',
            'denda'               => 'nullable|integer|min:0',
            'ulasan_denda'        => 'nullable|string|max:255',
            'status_transaksi'    => 'required|in:Aktif,Selesai,Batal,Disewa',
            'bukti_pembayaran'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('bukti_pembayaran');

        if ($request->hasFile('bukti_pembayaran')) {
            if ($transaksi->bukti_pembayaran &&
                Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
                Storage::disk('public')->delete($transaksi->bukti_pembayaran);
            }
            $data['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                ->store('bukti', 'public');
        }

        $transaksi->update($data);

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transaksi = TransaksiSewa::findOrFail($id);

        if ($transaksi->bukti_pembayaran &&
            Storage::disk('public')->exists($transaksi->bukti_pembayaran)) {
            Storage::disk('public')->delete($transaksi->bukti_pembayaran);
        }

        $transaksi->delete();

        return redirect()->route('admin.transaksi.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }

    /**
     * Tandai transaksi sebagai Selesai (Verify Payment)
     */
    public function selesai($id)
    {
        $transaksi = TransaksiSewa::findOrFail($id);
        $transaksi->update(['status_transaksi' => 'Selesai']);

        // Kembalikan status mobil → Tersedia
        Mobil::where('id_mobil', $transaksi->id_mobil)
            ->update(['status_ketersediaan' => 'Tersedia']);

        return back()->with('success', 'Transaksi berhasil diverifikasi dan ditandai Selesai.');
    }
}