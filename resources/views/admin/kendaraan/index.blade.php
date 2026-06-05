@extends('layouts.admin')

@section('title', 'Manajemen Kendaraan')
@section('page-title', 'Manajemen Kendaraan')
@section('page-subtitle', 'Selamat datang di SIREMO')

@push('styles')
<style>
    /* ── SEARCH BAR OVERRIDE ─────────────── */
    .search-box { width: 320px; }

    /* ── GRID KENDARAAN ──────────────────── */
    .kendaraan-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    @media (max-width: 1200px) { .kendaraan-grid { grid-template-columns: repeat(3,1fr); } }
    @media (max-width: 900px)  { .kendaraan-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width: 560px)  { .kendaraan-grid { grid-template-columns: 1fr; } }

    /* ── CARD ────────────────────────────── */
    .car-card {
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        position: relative;
        transition: transform .2s, box-shadow .2s;
        display: flex;
        flex-direction: column;
    }

    .car-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(232,98,42,.15);
    }

    /* ── STATUS BADGE TOP-RIGHT ─────────── */
    .car-status-badge {
        position: absolute;
        top: 12px; right: 12px;
        font-size: 10.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        z-index: 2;
        letter-spacing: .3px;
    }

    .badge-tersedia  { background: #D4F5E2; color: #1A7A48; }
    .badge-disewa    { background: #FFF0D6; color: #A86012; }
    .badge-perawatan { background: #FFE8E8; color: #A81212; }

    /* ── FOTO MOBIL ──────────────────────── */
    .car-img-wrap {
        width: 100%;
        height: 160px;
        background: #F5EDE8;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .car-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .3s;
    }

    .car-card:hover .car-img-wrap img { transform: scale(1.04); }

    .car-img-placeholder {
        font-size: 48px;
        color: #D0B8AE;
    }

    /* ── CARD BODY ───────────────────────── */
    .car-body {
        padding: 14px 14px 10px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .car-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 6px;
        margin-bottom: 2px;
    }

    .car-name {
        font-size: 14px;
        font-weight: 700;
        color: #1A1A1A;
        line-height: 1.3;
    }

    .car-plat {
        font-size: 10px;
        color: #999;
        white-space: nowrap;
    }

    .car-meta {
        font-size: 11px;
        color: #7A7A7A;
        margin-bottom: 6px;
    }

    .car-desc {
        font-size: 11.5px;
        color: #555;
        line-height: 1.5;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* ── CARD FOOTER ─────────────────────── */
    .car-footer {
        padding: 10px 14px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #F5EDE8;
    }

    .car-price {
        font-size: 13px;
        font-weight: 700;
        color: #E8622A;
    }

    .car-actions { display: flex; gap: 6px; }

    .btn-icon {
        width: 30px; height: 30px;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        border: none;
        font-size: 14px;
        cursor: pointer;
        transition: opacity .15s;
        text-decoration: none;
    }

    .btn-icon:hover { opacity: .8; }
    .btn-edit  { background: #D6EAFF; color: #1255A8; }
    .btn-hapus { background: #FFE0DE; color: #A81212; }

    /* ── TAMBAH BUTTON ───────────────────── */
    .btn-tambah {
        background: #E8622A;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background .15s;
    }

    .btn-tambah:hover { background: #c9521e; color: #fff; }

    /* ── FILTER PILLS ────────────────────── */
    .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; }

    .pill {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: 1.5px solid #E0D0CA;
        background: #fff;
        color: #555;
        cursor: pointer;
        text-decoration: none;
        transition: all .15s;
    }

    .pill:hover, .pill.active {
        background: #E8622A;
        border-color: #E8622A;
        color: #fff;
    }

    /* ── EMPTY STATE ─────────────────────── */
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: #AAA;
    }

    .empty-state i { font-size: 48px; display: block; margin-bottom: 12px; }
</style>
@endpush

@section('content')

{{-- ── TOP BAR ACTIONS ───────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <a href="{{ route('admin.kendaraan.create') }}" class="btn-tambah">
        <i class="bi bi-plus-circle-fill"></i> Tambah Mobil Baru
    </a>

    <div class="filter-pills">
        <a href="{{ route('admin.kendaraan.index') }}"
           class="pill {{ !request('status') ? 'active' : '' }}">Semua</a>
        <a href="{{ route('admin.kendaraan.index', ['status' => 'Tersedia']) }}"
           class="pill {{ request('status') === 'Tersedia' ? 'active' : '' }}">Tersedia</a>
        <a href="{{ route('admin.kendaraan.index', ['status' => 'Disewa']) }}"
           class="pill {{ request('status') === 'Disewa' ? 'active' : '' }}">Disewa</a>
        <a href="{{ route('admin.kendaraan.index', ['status' => 'Perawatan']) }}"
           class="pill {{ request('status') === 'Perawatan' ? 'active' : '' }}">Perawatan</a>
    </div>
</div>

{{-- ── ALERT ─────────────────────────────────────────── --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── GRID KENDARAAN ────────────────────────────────── --}}
<div class="kendaraan-grid">
    @forelse($kendaraan as $mobil)
    <div class="car-card">

        {{-- Status Badge --}}
        @php
            $badgeClass = match($mobil->status_ketersediaan) {
                'Tersedia'  => 'badge-tersedia',
                'Disewa'    => 'badge-disewa',
                'Perawatan' => 'badge-perawatan',
                default     => 'badge-tersedia',
            };
        @endphp
        <span class="car-status-badge {{ $badgeClass }}">
            {{ $mobil->status_ketersediaan }}
        </span>

        {{-- Foto --}}
        <div class="car-img-wrap">
            @if($mobil->foto)
                <img src="{{ asset('storage/' . $mobil->foto) }}"
                     alt="{{ $mobil->merek }} {{ $mobil->model }}">
            @else
                <span class="car-img-placeholder"><i class="bi bi-car-front-fill"></i></span>
            @endif
        </div>

        {{-- Body --}}
        <div class="car-body">
            <div class="car-title-row">
                <span class="car-name">{{ $mobil->merek }} {{ $mobil->model }}</span>
                <span class="car-plat">{{ $mobil->plat_nomor }}</span>
            </div>
            <div class="car-meta">
                {{ $mobil->kategori }} &bull; {{ $mobil->warna }} &bull; {{ $mobil->tahun }}
            </div>
            <p class="car-desc">{{ $mobil->deskripsi ?? '-' }}</p>
        </div>

        {{-- Footer --}}
        <div class="car-footer">
            <span class="car-price">Rp. {{ number_format($mobil->tarif_sewa_per_hari, 0, ',', '.') }}/hari</span>
            <div class="car-actions">
                <a href="{{ route('admin.kendaraan.edit', $mobil->id_mobil) }}"
                    class="btn-icon btn-edit" title="Edit">
                    <i class="bi bi-pencil-fill"></i>
                </a>
                <button type="button"
                        class="btn-icon btn-hapus"
                        title="Hapus"
                        onclick="konfirmasiHapus({{ $mobil->id_mobil }}, '{{ $mobil->merek }} {{ $mobil->model }}')">
                    <i class="bi bi-trash-fill"></i>
                </button>
                {{-- Hidden delete form --}}
                <form id="form-hapus-{{ $mobil->id_mobil }}"
                      action="{{ route('admin.kendaraan.destroy', $mobil->id_mobil) }}"
                      method="POST" class="d-none">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>

    </div>
    @empty
    <div class="empty-state">
        <i class="bi bi-car-front"></i>
        <p class="fw-600">Belum ada kendaraan.</p>
        <a href="{{ route('admin.kendaraan.create') }}" class="btn-tambah mt-2">
            <i class="bi bi-plus-circle-fill"></i> Tambah Sekarang
        </a>
    </div>
    @endforelse
</div>

{{-- ── PAGINATION ────────────────────────────────────── --}}
@if($kendaraan->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $kendaraan->appends(request()->query())->links() }}
</div>
@endif

{{-- ── MODAL KONFIRMASI HAPUS ───────────────────────── --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body text-center py-4 px-4">
                <div style="font-size:48px;color:#E8622A;"><i class="bi bi-trash-fill"></i></div>
                <h5 class="fw-700 mt-2 mb-1">Hapus Kendaraan?</h5>
                <p class="text-muted mb-3" id="modal-hapus-nama"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger rounded-3 px-4 fw-600" id="btn-konfirmasi-hapus">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let targetFormId = null;

function konfirmasiHapus(id, nama) {
    targetFormId = 'form-hapus-' + id;
    document.getElementById('modal-hapus-nama').textContent = nama;
    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}

document.getElementById('btn-konfirmasi-hapus').addEventListener('click', function () {
    if (targetFormId) document.getElementById(targetFormId).submit();
});
</script>
@endpush