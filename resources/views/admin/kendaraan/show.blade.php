@extends('layouts.admin')

@section('title', 'Detail Kendaraan')
@section('page-title', 'Detail Kendaraan')
@section('page-subtitle', 'Manajemen Kendaraan')

@push('styles')
<style>
    .detail-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        max-width: 860px;
    }

    .detail-foto {
        width: 100%;
        height: 300px;
        object-fit: cover;
        background: #F5EDE8;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .detail-foto img { width: 100%; height: 100%; object-fit: cover; }

    .detail-foto-placeholder {
        font-size: 72px;
        color: #D0B8AE;
        text-align: center;
        padding: 60px;
        background: #FAF5F2;
    }

    .detail-body { padding: 28px 32px; }

    .detail-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 20px;
    }

    .detail-name { font-size: 24px; font-weight: 700; }
    .detail-plat { font-size: 13px; color: #999; margin-top: 2px; }

    .detail-price {
        font-size: 20px;
        font-weight: 700;
        color: #E8622A;
    }

    .status-badge-lg {
        display: inline-block;
        padding: 5px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-tersedia  { background: #D4F5E2; color: #1A7A48; }
    .badge-disewa    { background: #FFF0D6; color: #A86012; }
    .badge-perawatan { background: #FFE8E8; color: #A81212; }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    @media (max-width: 600px) { .info-grid { grid-template-columns: 1fr 1fr; } }

    .info-item { background: #FAF5F2; border-radius: 10px; padding: 14px 16px; }
    .info-item .label { font-size: 11px; color: #999; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .info-item .value { font-size: 15px; font-weight: 700; color: #1A1A1A; margin-top: 4px; }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #E8622A;
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 2px solid #F5EDE8;
    }

    .btn-edit-detail {
        background: #E8622A;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 22px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
    }

    .btn-edit-detail:hover { background: #c9521e; color: #fff; }

    .btn-back {
        background: #F5EDE8;
        color: #555;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
    }

    .btn-back:hover { background: #E8DDD8; color: #333; }
</style>
@endpush

@section('content')

<div class="detail-card">

    {{-- Foto --}}
    <div class="detail-foto">
        @if($kendaraan->foto)
            <img src="{{ asset('storage/' . $kendaraan->foto) }}" alt="{{ $kendaraan->merek }} {{ $kendaraan->model }}">
        @else
            <div class="detail-foto-placeholder w-100">
                <i class="bi bi-car-front-fill"></i>
                <p style="font-size:14px;color:#C0A898;margin-top:8px;">Tidak ada foto</p>
            </div>
        @endif
    </div>

    <div class="detail-body">

        {{-- Header --}}
        <div class="detail-header">
            <div>
                <div class="detail-name">{{ $kendaraan->merek }} {{ $kendaraan->model }}</div>
                <div class="detail-plat">{{ $kendaraan->plat_nomor }}</div>
                <div class="mt-2">
                    @php
                        $cls = match($kendaraan->status_ketersediaan) {
                            'Tersedia'  => 'badge-tersedia',
                            'Disewa'    => 'badge-disewa',
                            'Perawatan' => 'badge-perawatan',
                            default     => 'badge-tersedia',
                        };
                    @endphp
                    <span class="status-badge-lg {{ $cls }}">{{ $kendaraan->status_ketersediaan }}</span>
                </div>
            </div>
            <div class="text-end">
                <div class="detail-price">Rp {{ number_format($kendaraan->tarif_sewa_per_hari, 0, ',', '.') }}</div>
                <div style="font-size:12px;color:#999;">per hari</div>
            </div>
        </div>

        {{-- Info Grid --}}
        <div class="info-grid">
            <div class="info-item">
                <div class="label">Merek</div>
                <div class="value">{{ $kendaraan->merek }}</div>
            </div>
            <div class="info-item">
                <div class="label">Model</div>
                <div class="value">{{ $kendaraan->model }}</div>
            </div>
            <div class="info-item">
                <div class="label">Tahun</div>
                <div class="value">{{ $kendaraan->tahun }}</div>
            </div>
            <div class="info-item">
                <div class="label">Warna</div>
                <div class="value">{{ $kendaraan->warna }}</div>
            </div>
            <div class="info-item">
                <div class="label">Kategori</div>
                <div class="value">{{ $kendaraan->kategori ?? '-' }}</div>
            </div>
            <div class="info-item">
                <div class="label">Plat Nomor</div>
                <div class="value">{{ $kendaraan->plat_nomor }}</div>
            </div>
        </div>

        {{-- Deskripsi --}}
        @if($kendaraan->deskripsi)
        <div class="mb-4">
            <div class="section-title">Deskripsi</div>
            <p style="font-size:14px;color:#444;line-height:1.7;margin:0;">
                {{ $kendaraan->deskripsi }}
            </p>
        </div>
        @endif

        {{-- Actions --}}
        <div class="d-flex gap-3 align-items-center">
            <a href="{{ route('admin.kendaraan.edit', $kendaraan->id_mobil) }}" class="btn-edit-detail">
                <i class="bi bi-pencil-fill me-1"></i> Edit Kendaraan
            </a>
            <a href="{{ route('admin.kendaraan.index') }}" class="btn-back">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

    </div>
</div>

@endsection