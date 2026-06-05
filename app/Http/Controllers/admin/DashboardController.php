<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSewa;
use App\Models\Penyewa;
use App\Models\Mobil;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ══════════════════════════════════════════
        // STAT CARD 1: PENDAPATAN
        // - Nilai utama  : total pendapatan bulan ini (transaksi Selesai)
        // - Badge (+x%)  : perbandingan vs bulan lalu
        // ══════════════════════════════════════════
        $pendapatanBulanIni = TransaksiSewa::whereMonth('tgl_sewa', now()->month)
            ->whereYear('tgl_sewa', now()->year)
            ->where('status_transaksi', 'Selesai')
            ->sum(DB::raw('total_bayar + denda'));

        $pendapatanBulanLalu = TransaksiSewa::whereMonth('tgl_sewa', now()->subMonth()->month)
            ->whereYear('tgl_sewa', now()->subMonth()->year)
            ->where('status_transaksi', 'Selesai')
            ->sum(DB::raw('total_bayar + denda'));

        // Persentase perubahan pendapatan vs bulan lalu
        if ($pendapatanBulanLalu > 0) {
            $pctPendapatan = round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1);
        } else {
            $pctPendapatan = $pendapatanBulanIni > 0 ? 100 : 0;
        }

        // Pendapatan tambahan hari ini saja
        $pendapatanHariIni = TransaksiSewa::whereDate('tgl_sewa', today())
            ->where('status_transaksi', 'Selesai')
            ->sum(DB::raw('total_bayar + denda'));

        // ══════════════════════════════════════════
        // STAT CARD 2: ARMADA
        // - Nilai utama : armada tersedia saat ini
        // - Badge       : total seluruh armada
        // - Tambahan    : armada baru ditambahkan hari ini
        // ══════════════════════════════════════════
        $armadaTersedia   = Mobil::where('status_ketersediaan', 'Tersedia')->count();
        $totalMobil       = Mobil::count();
        $armadaBaruHariIni = Mobil::whereDate('created_at', today())->count();

        // ══════════════════════════════════════════
        // STAT CARD 3: PENYEWAAN AKTIF
        // - Nilai utama : transaksi aktif/disewa saat ini
        // - Badge       : transaksi baru hari ini
        // ══════════════════════════════════════════
        $penyewaanAktif    = TransaksiSewa::whereIn('status_transaksi', ['Aktif', 'Disewa'])->count();
        $penyewaanBaruHariIni = TransaksiSewa::whereDate('created_at', today())->count();

        // ══════════════════════════════════════════
        // STAT CARD 4: TOTAL PELANGGAN
        // - Nilai utama : semua pelanggan terdaftar
        // - Badge (+x)  : pelanggan baru hari ini
        // ══════════════════════════════════════════
        $totalPelanggan      = Penyewa::count();
        $pelangganBaruHariIni = Penyewa::whereDate('tgl_gabung', today())->count();

        // ══════════════════════════════════════════
        // CHART PENDAPATAN BULANAN (6 bulan terakhir)
        // ══════════════════════════════════════════
        $pendapatanBulanan = TransaksiSewa::select(
                DB::raw('MONTH(tgl_sewa) as bulan'),
                DB::raw('YEAR(tgl_sewa) as tahun'),
                DB::raw('SUM(total_bayar + denda) as total')
            )
            ->where('status_transaksi', 'Selesai')
            ->where('tgl_sewa', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

        $bulanLabels = [];
        $bulanData   = [];
        $namaBulan   = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        for ($i = 5; $i >= 0; $i--) {
            $tgl   = now()->subMonths($i);
            $bln   = (int) $tgl->format('n');
            $thn   = (int) $tgl->format('Y');
            $found = $pendapatanBulanan->first(fn($r) => $r->bulan == $bln && $r->tahun == $thn);
            $bulanLabels[] = $namaBulan[$bln - 1];
            $bulanData[]   = $found ? (int) $found->total : 0;
        }

        // ══════════════════════════════════════════
        // DONUT STATUS KENDARAAN
        // ══════════════════════════════════════════
        $statusKendaraan = Mobil::select('status_ketersediaan', DB::raw('count(*) as jumlah'))
            ->groupBy('status_ketersediaan')
            ->pluck('jumlah', 'status_ketersediaan');

        $tersedia  = $statusKendaraan['Tersedia']  ?? 0;
        $disewa    = $statusKendaraan['Disewa']    ?? 0;
        $perawatan = $statusKendaraan['Perawatan'] ?? 0;

        // ══════════════════════════════════════════
        // TABEL PENYEWA TERBARU
        // ══════════════════════════════════════════
        $penyewaTerbaru = TransaksiSewa::with(['mobil', 'penyewa'])
            ->latest('id_transaksi')
            ->limit(10)
            ->get();

        // ══════════════════════════════════════════
        // NOTIFIKASI (5 terbaru, belum dibaca)
        // ══════════════════════════════════════════
        $notifikasi      = Notifikasi::latest()->limit(20)->get();
        $notifBelumBaca  = Notifikasi::where('dibaca', false)->count();

        return view('admin.dashboard', compact(
            // Pendapatan
            'pendapatanBulanIni', 'pendapatanBulanLalu',
            'pctPendapatan', 'pendapatanHariIni',
            // Armada
            'armadaTersedia', 'totalMobil', 'armadaBaruHariIni',
            // Penyewaan
            'penyewaanAktif', 'penyewaanBaruHariIni',
            // Pelanggan
            'totalPelanggan', 'pelangganBaruHariIni',
            // Chart
            'bulanLabels', 'bulanData',
            // Donut
            'tersedia', 'disewa', 'perawatan',
            // Tabel
            'penyewaTerbaru',
            // Notif
            'notifikasi', 'notifBelumBaca'
        ));
    }

    /**
     * API endpoint: ambil notifikasi terbaru (polling tiap 30 detik)
     */
    public function notifikasiTerbaru()
    {
        $notifikasi     = Notifikasi::latest()->limit(20)->get();
        $belumBaca      = Notifikasi::where('dibaca', false)->count();

        return response()->json([
            'notifikasi' => $notifikasi,
            'belum_baca' => $belumBaca,
        ]);
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function bacaSemuaNotifikasi()
    {
        Notifikasi::where('dibaca', false)->update(['dibaca' => true]);
        return response()->json(['status' => 'ok']);
    }

    /**
     * API endpoint: stat card terbaru (polling tiap 60 detik)
     */
    public function statTerbaru()
    {
        $pendapatanBulanIni = TransaksiSewa::whereMonth('tgl_sewa', now()->month)
            ->whereYear('tgl_sewa', now()->year)
            ->where('status_transaksi', 'Selesai')
            ->sum(DB::raw('total_bayar + denda'));

        $pendapatanBulanLalu = TransaksiSewa::whereMonth('tgl_sewa', now()->subMonth()->month)
            ->whereYear('tgl_sewa', now()->subMonth()->year)
            ->where('status_transaksi', 'Selesai')
            ->sum(DB::raw('total_bayar + denda'));

        $pctPendapatan = $pendapatanBulanLalu > 0
            ? round((($pendapatanBulanIni - $pendapatanBulanLalu) / $pendapatanBulanLalu) * 100, 1)
            : ($pendapatanBulanIni > 0 ? 100 : 0);

        return response()->json([
            'pendapatan_bulan_ini'    => $pendapatanBulanIni,
            'pendapatan_hari_ini'     => TransaksiSewa::whereDate('tgl_sewa', today())->where('status_transaksi','Selesai')->sum(DB::raw('total_bayar + denda')),
            'pct_pendapatan'          => $pctPendapatan,
            'armada_tersedia'         => Mobil::where('status_ketersediaan','Tersedia')->count(),
            'total_mobil'             => Mobil::count(),
            'armada_baru_hari_ini'    => Mobil::whereDate('created_at', today())->count(),
            'penyewaan_aktif'         => TransaksiSewa::whereIn('status_transaksi',['Aktif','Disewa'])->count(),
            'penyewaan_baru_hari_ini' => TransaksiSewa::whereDate('created_at', today())->count(),
            'total_pelanggan'         => Penyewa::count(),
            'pelanggan_baru_hari_ini' => Penyewa::whereDate('tgl_gabung', today())->count(),
        ]);
    }
}