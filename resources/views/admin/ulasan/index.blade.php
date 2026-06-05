@extends('layouts.admin')

@section('title', 'Ulasan Pelanggan')

@section('page-title', 'Ulasan Pelanggan')
@section('page-subtitle', 'Selamat datang di SIREMO')

@section('content')

<div class="ulasan-page">
    {{-- ===== FLASH MESSAGE ===== --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ===== SEARCH & FILTER BAR ===== --}}
    <form method="GET" action="{{ route('admin.ulasan.index') }}" class="filter-bar mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-7">
                <div class="search-wrap">
                    <i class="bi bi-search search-icon"></i>
                    <input
                        type="text"
                        name="search"
                        class="form-control search-input"
                        placeholder="Cari nama atau ulasan..."
                        value="{{ request('search') }}"
                    >
                </div>
            </div>
            <div class="col-md-3">
                <select name="rating" class="form-select filter-select">
                    <option value="">Semua Rating</option>
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} Bintang
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-filter w-100">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
            </div>
        </div>
    </form>

    {{-- ===== STATS ROW ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ number_format($rataRating, 1) }}</div>
                    <div class="stat-label">Rata-rata Rating</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <i class="bi bi-chat-left-text-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalUlasan }}</div>
                    <div class="stat-label">Total Ulasan</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon icon-green">
                    <i class="bi bi-hand-thumbs-up-fill"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $distribusi->get(5, 0) + $distribusi->get(4, 0) }}</div>
                    <div class="stat-label">Ulasan Positif (4-5 ★)</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== ULASAN CARDS GRID ===== --}}
    @if ($ulasan->isEmpty())
        <div class="empty-state">
            <i class="bi bi-chat-square-text empty-icon"></i>
            <p class="empty-text">Belum ada ulasan yang tersedia.</p>
        </div>
    @else
        <div class="ulasan-grid">
            @foreach ($ulasan as $item)
                <div class="ulasan-card">
                    {{-- Car info header --}}
                    <div class="card-header-row">
                        <div class="car-image-wrap">
                            {{-- Perbaikan logika pengecekan gambar foto --}}
                            @if ($item->mobil && $item->mobil->foto && file_exists(storage_path('app/public/' . $item->mobil->foto)))
                                <img
                                    src="{{ asset('storage/' . $item->mobil->foto) }}"
                                    alt="{{ $item->mobil->nama_lengkap ?? 'Mobil' }}"
                                    class="car-image"
                                >
                            @elseif ($item->mobil && $item->mobil->foto)
                                <img
                                    src="{{ asset('storage/' . $item->mobil->foto) }}"
                                    alt="{{ $item->mobil->nama_lengkap ?? 'Mobil' }}"
                                    class="car-image"
                                >
                            @else
                                <div class="car-image-placeholder">
                                    <i class="bi bi-car-front-fill"></i>
                                </div>
                            @endif
                        </div>

                        <div class="car-detail">
                            {{-- Menampilkan nama lengkap dari gabungan merek & model --}}
                            <div class="car-name">
                                {{ $item->mobil->nama_lengkap ?? ($item->mobil ? $item->mobil->merek . ' ' . $item->mobil->model : 'Mobil Tidak Diketahui') }}
                            </div>
                            <div class="car-plat">
                                {{ $item->mobil->plat_nomor ?? '-' }}
                            </div>

                            {{-- Star Rating --}}
                            <div class="star-rating">
                                @for ($s = 1; $s <= 5; $s++)
                                    @if ($s <= $item->rating)
                                        <i class="bi bi-star-fill star star-filled"></i>
                                    @else
                                        <i class="bi bi-star star star-empty"></i>
                                    @endif
                                @endfor
                            </div>

                            <div class="reviewer-name">
                                {{ $item->penyewa->nama ?? $item->nama ?? '-' }}
                            </div>
                            <div class="review-date">
                                {{ \Carbon\Carbon::parse($item->tanggal)->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <hr class="card-divider">

                    {{-- Review text --}}
                    <p class="review-text">
                        {{ $item->ulasan ?? '-' }}
                    </p>

                    {{-- Delete button --}}
                    <div class="card-actions">
                        <form
                            action="{{ route('admin.ulasan.destroy', $item->id_ulasan) }}"
                            method="POST"
                            onsubmit="return confirm('Hapus ulasan ini?')"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">
                                <i class="bi bi-trash3"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="pagination-wrap mt-4">
            {{ $ulasan->links() }}
        </div>
    @endif

</div>

@endsection


@push('styles')
<style>
/* ============================================================
   ULASAN PAGE — SIREMO Admin
   ============================================================ */

/* Page header */
.ulasan-page {
    padding: 0 4px;
}

.page-title {
    font-size: 1.9rem;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 2px;
}

.page-subtitle {
    font-size: 1rem;
    color: #7a7a8c;
    margin-bottom: 0;
}

/* ---- Search bar ---- */
.search-wrap {
    position: relative;
}
.search-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #aaa;
    font-size: 0.95rem;
}
.search-input {
    padding-left: 38px;
    border-radius: 10px;
    border: 1.5px solid #e5e5e5;
    height: 42px;
    font-size: 0.9rem;
    transition: border-color 0.2s;
}
.search-input:focus {
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
}
.filter-select {
    border-radius: 10px;
    border: 1.5px solid #e5e5e5;
    height: 42px;
    font-size: 0.9rem;
}
.filter-select:focus {
    border-color: #f97316;
    box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
}
.btn-filter {
    background: #f97316;
    color: #fff;
    border: none;
    border-radius: 10px;
    height: 42px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: background 0.2s;
}
.btn-filter:hover {
    background: #ea6c0a;
    color: #fff;
}

/* ---- Stat Cards ---- */
.stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: #fff7ed;
    color: #f97316;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.stat-icon.icon-blue  { background: #eff6ff; color: #3b82f6; }
.stat-icon.icon-green { background: #f0fdf4; color: #22c55e; }
.stat-value {
    font-size: 1.55rem;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1;
}
.stat-label {
    font-size: 0.78rem;
    color: #9ca3af;
    margin-top: 2px;
}

/* ---- Ulasan Grid ---- */
.ulasan-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}

@media (max-width: 1100px) {
    .ulasan-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 700px) {
    .ulasan-grid { grid-template-columns: 1fr; }
}

/* ---- Ulasan Card ---- */
.ulasan-card {
    background: #fff;
    border-radius: 18px;
    padding: 20px 20px 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    display: flex;
    flex-direction: column;
    transition: transform 0.18s, box-shadow 0.18s;
}
.ulasan-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.11);
}

/* Card header: image + detail */
.card-header-row {
    display: flex;
    align-items: flex-start;
    gap: 14px;
}

.car-image-wrap {
    width: 110px;
    height: 78px;
    flex-shrink: 0;
    border-radius: 10px;
    overflow: hidden;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
}
.car-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.car-image-placeholder {
    font-size: 2.5rem;
    color: #d1d5db;
}

.car-detail {
    flex: 1;
    min-width: 0;
}

.car-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1.2;
}
.car-plat {
    font-size: 0.72rem;
    color: #9ca3af;
    margin-bottom: 5px;
    letter-spacing: 0.03em;
}

/* Stars */
.star-rating {
    display: flex;
    gap: 2px;
    margin-bottom: 6px;
}
.star {
    font-size: 0.88rem;
}
.star-filled { color: #f59e0b; }
.star-empty  { color: #d1d5db; }

.reviewer-name {
    font-size: 0.88rem;
    font-weight: 600;
    color: #374151;
}
.review-date {
    font-size: 0.75rem;
    color: #9ca3af;
    margin-top: 1px;
}

/* Divider */
.card-divider {
    border: none;
    border-top: 1px solid #f3f4f6;
    margin: 14px 0 10px;
}

/* Review text */
.review-text {
    font-size: 0.875rem;
    color: #4b5563;
    line-height: 1.55;
    flex: 1;
    margin-bottom: 14px;
}

/* Delete button */
.card-actions {
    display: flex;
    justify-content: flex-end;
}
.btn-delete {
    background: none;
    border: 1.5px solid #fca5a5;
    color: #ef4444;
    border-radius: 8px;
    padding: 5px 14px;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    display: flex;
    align-items: center;
    gap: 5px;
}
.btn-delete:hover {
    background: #ef4444;
    color: #fff;
    border-color: #ef4444;
}

/* ---- Empty state ---- */
.empty-state {
    text-align: center;
    padding: 80px 0;
    color: #9ca3af;
}
.empty-icon {
    font-size: 3.5rem;
    display: block;
    margin-bottom: 12px;
    color: #e5e7eb;
}
.empty-text {
    font-size: 1rem;
}

/* ---- Pagination ---- */
.pagination-wrap {
    display: flex;
    justify-content: center;
}
.pagination-wrap .page-link {
    color: #f97316;
    border-radius: 8px;
}
.pagination-wrap .page-item.active .page-link {
    background-color: #f97316;
    border-color: #f97316;
    color: #fff;
}
</style>
@endpush