<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 11px;
        color: #1A1A1A;
        background: #fff;
    }

    /* ── HEADER ──────────────────────────────── */
    .header {
        background: #E8622A;
        padding: 18px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0;
    }

    .header-left { display: flex; align-items: center; gap: 14px; }

    .header-logo-box {
        width: 44px; height: 44px;
        background: rgba(255,255,255,.25);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px;
        color: #fff;
        font-weight: 900;
        text-align: center;
        line-height: 44px;
    }

    .header-brand { color: #fff; }
    .header-brand .brand-name { font-size: 20px; font-weight: 900; letter-spacing: .5px; }
    .header-brand .brand-sub  { font-size: 10px; opacity: .85; }

    .header-right { text-align: right; color: #fff; }
    .header-right .doc-title  { font-size: 16px; font-weight: 700; }
    .header-right .doc-period { font-size: 11px; opacity: .9; margin-top: 2px; }
    .header-right .doc-date   { font-size: 10px; opacity: .75; margin-top: 1px; }

    /* ── STAT ROW ────────────────────────────── */
    .stat-row {
        background: #FFF0EA;
        padding: 14px 28px;
        display: flex;
        gap: 0;
        border-bottom: 2px solid #E8622A;
    }

    .stat-item {
        flex: 1;
        text-align: center;
        padding: 0 12px;
        border-right: 1px solid #E8C8B8;
    }

    .stat-item:last-child { border-right: none; }

    .stat-item .s-label {
        font-size: 9.5px;
        color: #7A4030;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 700;
        display: block;
        margin-bottom: 3px;
    }

    .stat-item .s-value {
        font-size: 15px;
        font-weight: 900;
        color: #E8622A;
    }

    .stat-item .s-value.green  { color: #1A7A48; }
    .stat-item .s-value.blue   { color: #1255A8; }
    .stat-item .s-value.red    { color: #A81212; }
    .stat-item .s-value.dark   { color: #1A1A1A; }

    /* ── TABLE ───────────────────────────────── */
    .table-wrap { padding: 16px 28px 20px; }

    .section-title {
        font-size: 12px;
        font-weight: 700;
        color: #E8622A;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 2px solid #F5EDE8;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead tr {
        background: #FFF0EA;
    }

    thead th {
        padding: 9px 10px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
        color: #7A4030;
        border-bottom: 2px solid #E8622A;
        text-align: left;
        white-space: nowrap;
    }

    tbody tr { border-bottom: 1px solid #F5EDE8; }
    tbody tr:nth-child(even) { background: #FFFAF8; }

    tbody td {
        padding: 8px 10px;
        font-size: 10.5px;
        vertical-align: middle;
        color: #333;
    }

    .td-no    { width: 32px; text-align: center; font-weight: 700; color: #999; }
    .td-unit  { max-width: 110px; }
    .td-unit .unit-name { font-weight: 700; font-size: 10.5px; color: #1A1A1A; }
    .td-unit .unit-plat { font-size: 9px; color: #999; }

    .td-penyewa .p-name  { font-weight: 700; }
    .td-penyewa .p-email { font-size: 9.5px; color: #888; }

    .td-durasi { font-size: 10px; color: #555; white-space: nowrap; }

    /* Status badges */
    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .4px;
        white-space: nowrap;
    }

    .badge-selesai  { background: #D4F5E2; color: #1A7A48; }
    .badge-aktif    { background: #D6EAFF; color: #1255A8; }
    .badge-disewa   { background: #FFF0D6; color: #A86012; }
    .badge-batal    { background: #FFE0DE; color: #A81212; }

    .td-harga  { text-align: right; font-weight: 700; color: #1A1A1A; white-space: nowrap; }
    .td-denda  { text-align: right; font-weight: 700; color: #A81212; white-space: nowrap; }
    .td-total  { text-align: right; font-weight: 900; color: #E8622A; white-space: nowrap; }

    /* ── FOOTER ──────────────────────────────── */
    .footer {
        background: #F5EDE8;
        padding: 10px 28px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 2px solid #E8622A;
        margin-top: 8px;
    }

    .footer-left  { font-size: 9.5px; color: #7A4030; }
    .footer-right { font-size: 9.5px; color: #7A4030; text-align: right; }

    /* ── EMPTY ───────────────────────────────── */
    .empty-row td {
        text-align: center;
        padding: 30px;
        color: #AAA;
        font-style: italic;
    }

    /* ── GRAND TOTAL ROW ─────────────────────── */
    .total-row td {
        background: #FFF0EA;
        font-weight: 900;
        font-size: 11px;
        padding: 10px 10px;
        border-top: 2px solid #E8622A;
    }

    .total-row .total-label { color: #E8622A; text-transform: uppercase; letter-spacing: .5px; }
</style>
</head>
<body>

{{-- ── HEADER ──────────────────────────────────────── --}}
<div class="header">
    <div class="header-left">
        <div class="header-logo-box">S</div>
        <div class="header-brand">
            <div class="brand-name">SIREMO</div>
            <div class="brand-sub">Sistem Informasi Rental Mobil</div>
        </div>
    </div>
    <div class="header-right">
        <div class="doc-title">Laporan Transaksi Sewa</div>
        <div class="doc-period">Periode: {{ $labelPeriode }}</div>
        <div class="doc-date">Dicetak: {{ now()->format('d/m/Y H:i') }} WIB</div>
    </div>
</div>

{{-- ── STAT CARDS ───────────────────────────────────── --}}
<div class="stat-row">
    <div class="stat-item">
        <span class="s-label">Total Pendapatan</span>
        <span class="s-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
    </div>
    <div class="stat-item">
        <span class="s-label">Total Denda</span>
        <span class="s-value red">Rp {{ number_format($totalDenda, 0, ',', '.') }}</span>
    </div>
    <div class="stat-item">
        <span class="s-label">Total Transaksi</span>
        <span class="s-value dark">{{ $jumlahTransaksi }}</span>
    </div>
    <div class="stat-item">
        <span class="s-label">Selesai</span>
        <span class="s-value green">{{ $jumlahSelesai }}</span>
    </div>
    <div class="stat-item">
        <span class="s-label">Aktif / Disewa</span>
        <span class="s-value blue">{{ $jumlahAktif }}</span>
    </div>
    <div class="stat-item">
        <span class="s-label">Batal</span>
        <span class="s-value red">{{ $jumlahBatal }}</span>
    </div>
</div>

{{-- ── TABLE ────────────────────────────────────────── --}}
<div class="table-wrap">
    <div class="section-title">Daftar Transaksi Sewa</div>

    <table>
        <thead>
            <tr>
                <th class="td-no">No</th>
                <th>Penyewa</th>
                <th>Unit Kendaraan</th>
                <th>Tgl Sewa</th>
                <th>Rencana Kembali</th>
                <th>Lama (Hari)</th>
                <th>Status</th>
                <th style="text-align:right;">Total Bayar</th>
                <th style="text-align:right;">Denda</th>
                <th style="text-align:right;">Grand Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $i => $t)
            <tr>
                <td class="td-no">{{ $i + 1 }}</td>

                <td class="td-penyewa">
                    <div class="p-name">{{ $t->penyewa->nama ?? '-' }}</div>
                    <div class="p-email">{{ $t->penyewa->email ?? '' }}</div>
                </td>

                <td class="td-unit">
                    <div class="unit-name">{{ ($t->mobil->merek ?? '') . ' ' . ($t->mobil->model ?? '-') }}</div>
                    <div class="unit-plat">{{ $t->mobil->plat_nomor ?? '' }}</div>
                </td>

                <td class="td-durasi">{{ $t->tgl_sewa?->format('d/m/Y') ?? '-' }}</td>

                <td class="td-durasi">{{ $t->tgl_rencana_kembali?->format('d/m/Y') ?? '-' }}</td>

                <td style="text-align:center;">{{ $t->lama_sewa_hari ?: '-' }}</td>

                <td>
                    @php
                        $cls = match($t->status_transaksi) {
                            'Selesai' => 'badge-selesai',
                            'Aktif'   => 'badge-aktif',
                            'Disewa'  => 'badge-disewa',
                            'Batal'   => 'badge-batal',
                            default   => 'badge-aktif',
                        };
                    @endphp
                    <span class="badge {{ $cls }}">{{ $t->status_transaksi }}</span>
                </td>

                <td class="td-harga">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>

                <td class="td-denda">
                    {{ $t->denda > 0 ? 'Rp ' . number_format($t->denda, 0, ',', '.') : '-' }}
                </td>

                <td class="td-total">
                    Rp {{ number_format($t->total_bayar + $t->denda, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="10">Tidak ada data transaksi pada periode ini.</td>
            </tr>
            @endforelse

            @if($transaksi->count() > 0)
            <tr class="total-row">
                <td colspan="7" class="total-label">Grand Total Pendapatan (Selesai)</td>
                <td style="text-align:right;">
                    Rp {{ number_format($transaksi->sum('total_bayar'), 0, ',', '.') }}
                </td>
                <td style="text-align:right;color:#A81212;">
                    Rp {{ number_format($totalDenda, 0, ',', '.') }}
                </td>
                <td style="text-align:right;color:#E8622A;">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>

{{-- ── FOOTER ───────────────────────────────────────── --}}
<div class="footer">
    <div class="footer-left">
        SIREMO &mdash; Sistem Informasi Rental Mobil &bull;
        Laporan Periode: <strong>{{ $labelPeriode }}</strong>
    </div>
    <div class="footer-right">
        Dokumen ini digenerate secara otomatis oleh sistem &bull;
        {{ now()->format('d F Y, H:i') }} WIB
    </div>
</div>

</body>
</html>