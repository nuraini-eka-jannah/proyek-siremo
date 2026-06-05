@extends('layouts.admin')

@section('title', 'Detail Transaksi')
@section('page-title', 'Detail Transaksi')
@section('page-subtitle', 'Riwayat Transaksi')

@push('styles')
<style>
    .detail-card {
        background: #fff;
        border-radius: 16px;
        padding: 28px 32px;
        max-width: 860px;
    }

    .section-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: #E8622A;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 2px solid #F5EDE8;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }

    @media (max-width: 600px) { .info-grid { grid-template-columns: 1fr 1fr; } }

    .info-item {
        background: #FAF5F2;
        border-radius: 10px;
        padding: 14px 16px;
    }

    .info-item .lbl {
        font-size: 11px;
        color: #999;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .info-item .val {
        font-size: 14px;
        font-weight: 700;
        color: #1A1A1A;
        margin-top: 4px;
    }

    .info-item .val.harga { color: #E8622A; font-size: 16px; }

    .status-pill {
        padding: 5px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }

    .status-selesai  { background: #D4F5E2; color: #1A7A48; }
    .status-aktif    { background: #D6EAFF; color: #1255A8; }
    .status-disewa   { background: #FFF0D6; color: #A86012; }
    .status-batal    { background: #FFE0DE; color: #A81212; }
    .status-menunggu { background: #FFE8D6; color: #C05000; }

    .bukti-img {
        max-width: 320px;
        border-radius: 12px;
        border: 1.5px solid #E8DDD8;
    }

    .btn-back {
        background: #F5EDE8;
        color: #555;
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
    }

    .btn-back:hover { background: #E8DDD8; color: #333; }

    .btn-verify {
        background: #E8622A;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-size: 13.5px;
        font-weight: 600;
        transition: background .15s;
    }

    .btn-verify:hover { background: #c9521e; color: #fff; }
</style>
@endpush

@section('content')

<div class="detail-card">

    <div class="mb-4">
        <a href="{{ route('admin.transaksi.index') }}" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    {{-- Header ID & Status --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-700 mb-1">TR - {{ $transaksi->id_transaksi }}</h4>
            <div style="font-size:13px;color:#999;">
                {{ \Carbon\Carbon::parse($transaksi->tgl_sewa)->translatedFormat('d F Y, H:i') }}
            </div>
        </div>
        @php
            $statusMap = [
                'Selesai' => ['css' => 'status-selesai',  'label' => 'LUNAS'],
                'Aktif'   => ['css' => 'status-aktif',    'label' => 'AKTIF'],
                'Disewa'  => ['css' => 'status-disewa',   'label' => 'DISEWA'],
                'Batal'   => ['css' => 'status-batal',    'label' => 'BATAL'],
            ];
            $st = $statusMap[$transaksi->status_transaksi]
                ?? ['css' => 'status-menunggu', 'label' => 'MENUNGGU'];
        @endphp
        <span class="status-pill {{ $st['css'] }}" style="font-size:14px;padding:7px 20px;">
            {{ $st['label'] }}
        </span>
    </div>

    {{-- Info Pelanggan --}}
    <div class="section-title">Data Pelanggan</div>
    <div class="info-grid mb-4">
        <div class="info-item">
            <div class="lbl">Nama</div>
            <div class="val">{{ $transaksi->penyewa->nama ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="lbl">Email</div>
            <div class="val">{{ $transaksi->penyewa->email ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="lbl">No. Telepon</div>
            <div class="val">{{ $transaksi->penyewa->no_telepon ?? '-' }}</div>
        </div>
    </div>

    {{-- Info Kendaraan --}}
    <div class="section-title">Kendaraan Disewa</div>
    <div class="info-grid mb-4">
        <div class="info-item">
            <div class="lbl">Nama Mobil</div>
            <div class="val">{{ $transaksi->mobil->merek ?? '' }} {{ $transaksi->mobil->model ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="lbl">Plat Nomor</div>
            <div class="val">{{ $transaksi->mobil->plat_nomor ?? '-' }}</div>
        </div>
        <div class="info-item">
            <div class="lbl">Tarif / Hari</div>
            <div class="val">Rp {{ number_format($transaksi->mobil->tarif_sewa_per_hari ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Info Sewa --}}
    <div class="section-title">Detail Sewa</div>
    <div class="info-grid mb-4">
        <div class="info-item">
            <div class="lbl">Tanggal Sewa</div>
            <div class="val">{{ \Carbon\Carbon::parse($transaksi->tgl_sewa)->format('d M Y') }}</div>
        </div>
        <div class="info-item">
            <div class="lbl">Rencana Kembali</div>
            <div class="val">{{ \Carbon\Carbon::parse($transaksi->tgl_rencana_kembali)->format('d M Y') }}</div>
        </div>
        <div class="info-item">
            <div class="lbl">Aktual Kembali</div>
            <div class="val">
                {{ $transaksi->tgl_aktual_kembali
                    ? \Carbon\Carbon::parse($transaksi->tgl_aktual_kembali)->format('d M Y')
                    : '-' }}
            </div>
        </div>
        <div class="info-item">
            <div class="lbl">Lama Sewa</div>
            <div class="val">{{ $transaksi->lama_sewa_hari }} hari</div>
        </div>
        <div class="info-item">
            <div class="lbl">Total Bayar</div>
            <div class="val harga">Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</div>
        </div>
        <div class="info-item">
            <div class="lbl">Denda</div>
            <div class="val" style="{{ $transaksi->denda > 0 ? 'color:#E8622A;' : '' }}">
                Rp {{ number_format($transaksi->denda, 0, ',', '.') }}
                @if($transaksi->denda > 0 && $transaksi->ulasan_denda)
                    <div style="font-size:11px;color:#999;font-weight:400;margin-top:2px;">
                        {{ $transaksi->ulasan_denda }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Total Tagihan --}}
    <div style="background:#FFF0EA;border-radius:12px;padding:16px 20px;margin-bottom:24px;
                display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:15px;font-weight:700;color:#555;">Total Tagihan Keseluruhan</span>
        <span style="font-size:22px;font-weight:700;color:#E8622A;">
            Rp {{ number_format($transaksi->total_bayar + $transaksi->denda, 0, ',', '.') }}
        </span>
    </div>

    {{-- Bukti Pembayaran --}}
    @if($transaksi->bukti_pembayaran)
    <div class="section-title">Bukti Pembayaran</div>
    <div class="mb-4">
        @php
            $isFoto = str_contains($transaksi->bukti_pembayaran, '.') ||
                      str_contains($transaksi->bukti_pembayaran, '/');
        @endphp
        @if($isFoto)
            <img src="{{ asset('storage/' . $transaksi->bukti_pembayaran) }}"
                 alt="Bukti Pembayaran" class="bukti-img">
        @else
            <div style="background:#F5EDE8;border-radius:10px;padding:14px 18px;display:inline-block;">
                <i class="bi bi-receipt me-2" style="color:#E8622A;"></i>
                <span style="font-size:13px;font-weight:600;">{{ $transaksi->bukti_pembayaran }}</span>
            </div>
        @endif
    </div>
    @endif

    {{-- Actions --}}
    <div class="d-flex gap-3 align-items-center flex-wrap">
        @if(!in_array($transaksi->status_transaksi, ['Selesai', 'Batal']))
            <form action="{{ route('admin.transaksi.selesai', $transaksi->id_transaksi) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn-verify"
                        onclick="return confirm('Tandai transaksi ini sebagai Selesai/Lunas?')">
                    <i class="bi bi-check-circle-fill me-1"></i> Verify Payment
                </button>
            </form>
        @endif
    </div>

</div>

@endsection