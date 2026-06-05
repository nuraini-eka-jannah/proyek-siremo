<?php

namespace App\Observers;

use App\Models\TransaksiSewa;
use App\Models\Notifikasi;

class TransaksiSewaObserver
{
    /**
     * Saat transaksi baru dibuat → notif "Pesanan Sewa Baru"
     */
    public function created(TransaksiSewa $transaksi): void
    {
        $namaPenyewa = $transaksi->penyewa->nama  ?? 'Pelanggan';
        $namaMobil   = ($transaksi->mobil->merek ?? '') . ' ' . ($transaksi->mobil->model ?? '');

        Notifikasi::create([
            'judul'        => 'Pesanan Sewa Baru',
            'pesan'        => "{$namaPenyewa} menyewa {$namaMobil}",
            'tipe'         => 'pesanan_sewa',
            'icon'         => Notifikasi::iconTipe('pesanan_sewa'),
            'warna'        => Notifikasi::warnaTipe('pesanan_sewa'),
            'url'          => '/admin/transaksi/' . $transaksi->id_transaksi,
            'dibaca'       => false,
            'id_transaksi' => $transaksi->id_transaksi,
        ]);
    }

    /**
     * Saat status berubah → cek tipe notif yang sesuai
     */
    public function updated(TransaksiSewa $transaksi): void
    {
        // Hanya proses jika status_transaksi berubah
        if (! $transaksi->wasChanged('status_transaksi')) return;

        $namaPenyewa = $transaksi->penyewa->nama  ?? 'Pelanggan';
        $namaMobil   = ($transaksi->mobil->merek ?? '') . ' ' . ($transaksi->mobil->model ?? '');
        $status      = $transaksi->status_transaksi;

        // ── Pembayaran sukses (status → Selesai) ──
        if ($status === 'Selesai' && is_null($transaksi->tgl_aktual_kembali)) {
            Notifikasi::create([
                'judul'        => 'Pembayaran Dikonfirmasi',
                'pesan'        => "Pembayaran dari {$namaPenyewa} untuk {$namaMobil} telah sukses",
                'tipe'         => 'pembayaran',
                'icon'         => Notifikasi::iconTipe('pembayaran'),
                'warna'        => Notifikasi::warnaTipe('pembayaran'),
                'url'          => '/admin/transaksi/' . $transaksi->id_transaksi,
                'dibaca'       => false,
                'id_transaksi' => $transaksi->id_transaksi,
            ]);
        }

        // ── Pengembalian berhasil (tgl_aktual_kembali terisi + status Selesai) ──
        if ($status === 'Selesai' && $transaksi->wasChanged('tgl_aktual_kembali') && $transaksi->tgl_aktual_kembali) {
            Notifikasi::create([
                'judul'        => 'Kendaraan Dikembalikan',
                'pesan'        => "{$namaMobil} telah dikembalikan oleh {$namaPenyewa}",
                'tipe'         => 'pengembalian',
                'icon'         => Notifikasi::iconTipe('pengembalian'),
                'warna'        => Notifikasi::warnaTipe('pengembalian'),
                'url'          => '/admin/pengembalian',
                'dibaca'       => false,
                'id_transaksi' => $transaksi->id_transaksi,
            ]);
        }
    }
}