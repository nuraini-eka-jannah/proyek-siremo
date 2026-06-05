<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransaksiSewa;
use App\Models\Mobil;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Halaman pilih periode sebelum cetak
     */
    public function index()
    {
        $bulanList = [];
        for ($i = 0; $i < 12; $i++) {
            $tgl = now()->subMonths($i);
            $bulanList[] = [
                'value' => $tgl->format('Y-m'),
                'label' => $this->namaBulan($tgl->format('m')) . ' ' . $tgl->format('Y'),
            ];
        }

        return view('admin.laporan.index', compact('bulanList'));
    }

    /**
     * Generate & download PDF
     */
    public function cetak(Request $request)
    {
        $bulan = $request->input('bulan', now()->format('Y-m'));

        $query = TransaksiSewa::with(['mobil', 'penyewa'])
            ->orderBy('tgl_sewa', 'desc');

        if ($bulan !== 'semua') {
            [$tahun, $bln] = explode('-', $bulan);
            $query->whereYear('tgl_sewa', $tahun)
                  ->whereMonth('tgl_sewa', $bln);
        }

        $transaksi = $query->get();

        // ── Statistik ringkasan ───────────────────────
        $totalPendapatan = $transaksi
            ->where('status_transaksi', 'Selesai')
            ->sum(fn($t) => $t->total_bayar + $t->denda);

        $totalDenda      = $transaksi->sum('denda');
        $jumlahTransaksi = $transaksi->count();
        $jumlahSelesai   = $transaksi->where('status_transaksi', 'Selesai')->count();
        $jumlahAktif     = $transaksi->whereIn('status_transaksi', ['Aktif','Disewa'])->count();
        $jumlahBatal     = $transaksi->where('status_transaksi', 'Batal')->count();

        // ── Label periode ─────────────────────────────
        if ($bulan === 'semua') {
            $labelPeriode = 'Semua Periode';
        } else {
            [$tahun, $bln] = explode('-', $bulan);
            $labelPeriode  = $this->namaBulan($bln) . ' ' . $tahun;
        }

        $pdf = Pdf::loadView('admin.laporan.pdf', compact(
            'transaksi', 'totalPendapatan', 'totalDenda',
            'jumlahTransaksi', 'jumlahSelesai', 'jumlahAktif', 'jumlahBatal',
            'labelPeriode'
        ))->setPaper('a4', 'landscape');

        $namaFile = 'laporan-siremo-' . str_replace(' ', '-', strtolower($labelPeriode)) . '.pdf';

        return $pdf->download($namaFile);
    }

    private function namaBulan(string $bln): string
    {
        return [
            '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
            '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
            '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
        ][$bln] ?? $bln;
    }
}