<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenyewaController extends Controller
{
    public function index(Request $request)
    {
        $query = Penyewa::withCount('transaksiSewa as transaksi_count')
            ->orderBy('id_penyewa', 'desc');

        // Filter pencarian nama / email / no_ktp
        if ($request->filled('search')) {
            $cari = $request->search;
            $query->where(function ($q) use ($cari) {
                $q->where('nama',       'like', "%{$cari}%")
                  ->orWhere('email',    'like', "%{$cari}%")
                  ->orWhere('no_ktp',   'like', "%{$cari}%")
                  ->orWhere('no_telepon','like', "%{$cari}%");
            });
        }

        $pelanggan = $query->paginate(15)->withQueryString();

        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'       => 'required|string|max:100',
            'alamat'     => 'nullable|string',
            'no_ktp'     => 'nullable|string|max:20',
            'no_sim'     => 'nullable|string|max:20',
            'no_telepon' => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:100',
            'foto_sim'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('foto_sim');

        if ($request->hasFile('foto_sim')) {
            $data['foto_sim'] = $request->file('foto_sim')->store('sim', 'public');
        }

        Penyewa::create($data);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pelanggan = Penyewa::with('transaksiSewa.mobil')
            ->withCount('transaksiSewa as transaksi_count')
            ->findOrFail($id);

        return view('admin.pelanggan.show', compact('pelanggan'));
    }

    public function edit($id)
    {
        $pelanggan = Penyewa::findOrFail($id);
        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Penyewa::findOrFail($id);

        $request->validate([
            'nama'       => 'required|string|max:100',
            'alamat'     => 'nullable|string',
            'no_ktp'     => 'nullable|string|max:20',
            'no_sim'     => 'nullable|string|max:20',
            'no_telepon' => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:100',
            'foto_sim'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('foto_sim');

        if ($request->hasFile('foto_sim')) {
            if ($pelanggan->foto_sim && Storage::disk('public')->exists($pelanggan->foto_sim)) {
                Storage::disk('public')->delete($pelanggan->foto_sim);
            }
            $data['foto_sim'] = $request->file('foto_sim')->store('sim', 'public');
        }

        $pelanggan->update($data);

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pelanggan = Penyewa::findOrFail($id);

        if ($pelanggan->foto_sim && Storage::disk('public')->exists($pelanggan->foto_sim)) {
            Storage::disk('public')->delete($pelanggan->foto_sim);
        }

        $pelanggan->delete();

        return redirect()->route('admin.pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }
}