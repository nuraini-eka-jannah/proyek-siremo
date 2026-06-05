@extends('layouts.admin')

@section('title', 'Cetak Laporan')
@section('page-title', 'Cetak Laporan')
@section('page-subtitle', 'Generate laporan dalam format PDF')

@push('styles')
<style>
    .laporan-card {
        background: #fff;
        border-radius: 16px;
        padding: 32px 36px;
        max-width: 560px;
    }

    .laporan-icon {
        width: 56px; height: 56px;
        background: #FFF0EA;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 26px;
        color: #E8622A;
        margin-bottom: 18px;
    }

    .laporan-title { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
    .laporan-sub   { font-size: 13px; color: #7A7A7A; margin-bottom: 28px; }

    .form-label { font-size: 13px; font-weight: 600; color: #333; margin-bottom: 6px; }

    .form-select {
        border-radius: 10px;
        border: 1.5px solid #E8DDD8;
        font-size: 13.5px;
        padding: 10px 14px;
        transition: border-color .15s, box-shadow .15s;
    }

    .form-select:focus {
        border-color: #E8622A;
        box-shadow: 0 0 0 3px rgba(232,98,42,.12);
    }

    .divider {
        border: none;
        border-top: 2px dashed #F5EDE8;
        margin: 24px 0;
    }

    .btn-cetak {
        background: #E8622A;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 28px;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: background .15s;
        text-decoration: none;
    }

    .btn-cetak:hover { background: #c9521e; color: #fff; }

    .btn-back {
        background: #F5EDE8;
        color: #555;
        border: none;
        border-radius: 10px;
        padding: 12px 22px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
    }

    .btn-back:hover { background: #E8DDD8; color: #333; }

    /* Info box */
    .info-box {
        background: #FFF8F5;
        border: 1.5px solid #F5DDD0;
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 12.5px;
        color: #7A4030;
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .info-box i { font-size: 16px; color: #E8622A; flex-shrink: 0; margin-top: 1px; }
</style>
@endpush

@section('content')

<div class="laporan-card">

    <div class="laporan-icon"><i class="bi bi-file-earmark-pdf-fill"></i></div>
    <div class="laporan-title">Cetak Laporan PDF</div>
    <div class="laporan-sub">Pilih periode laporan yang ingin dicetak lalu klik tombol Cetak.</div>

    <form action="{{ route('admin.laporan.cetak') }}" method="GET" target="_blank">

        <div class="mb-4">
            <label class="form-label">Pilih Periode</label>
            <select name="bulan" class="form-select">
                <option value="semua">📋 Semua Periode</option>
                @foreach($bulanList as $b)
                    <option value="{{ $b['value'] }}">{{ $b['label'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="info-box mb-4">
            <i class="bi bi-info-circle-fill"></i>
            <div>
                Laporan berisi ringkasan pendapatan, denda, status transaksi, dan daftar
                lengkap semua penyewaan pada periode yang dipilih.
                File akan otomatis terunduh sebagai <strong>.pdf</strong>.
            </div>
        </div>

        <hr class="divider">

        <div class="d-flex gap-3 align-items-center">
            <button type="submit" class="btn-cetak">
                <i class="bi bi-printer-fill"></i> Cetak Laporan
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn-back">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

    </form>
</div>

@endsection