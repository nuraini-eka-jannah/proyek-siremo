@extends('layouts.admin')

@section('title', 'Daftar Pelanggan')
@section('page-title', 'Daftar Pelanggan')
@section('page-subtitle', 'Selamat datang di SIREMO')

@push('styles')
<style>
    /* Mengunci ukuran ikon svg pagination agar kecil dan rapi */
    .pagination svg {
        width: 16px !important;
        height: 16px !important;
    }
    
    /* Merapikan navigasi tulisan info teks di bawah tombol */
    .pagination .flex {
        display: inline-flex;
        align-items: center;
    }
    /* ── TABLE CARD ──────────────────────────── */
    .pelanggan-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
    }

    /* ── TABLE HEAD ──────────────────────────── */
    .table-pelanggan thead tr {
        background: #ed743c;
    }

    .table-pelanggan thead th {
        font-size: 14px;
        font-weight: 700;
        color: #333;
        border-bottom: 2px solid #e77946; /* garis pemisah */
        padding: 18px 20px;
        border: none;
        white-space: nowrap;
        vertical-align: middle;
    }

    /* ── TABLE BODY ──────────────────────────── */
    .table-pelanggan tbody td {
        padding: 18px 20px;
        font-size: 13.5px;
        vertical-align: middle;
        border-color: #f5a97a;
        color: #222;
    }

    .table-pelanggan tbody tr:hover {
        background: #FFFAF8;
    }

    /* ── NAMA ────────────────────────────────── */
    .td-nama {
        font-weight: 700;
        font-size: 14px;
        color: #1A1A1A;
        min-width: 160px;
    }

    /* ── EMAIL ───────────────────────────────── */
    .td-email {
        color: #444;
        font-size: 13px;
    }

    /* ── IDENTITAS ───────────────────────────── */
    .td-identitas {
        min-width: 180px;
    }

    .id-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #333;
        line-height: 1.8;
    }

    .id-label {
        font-weight: 700;
        color: #555;
        min-width: 36px;
    }

    /* ── FOTO SIM ────────────────────────────── */
    .td-sim { min-width: 110px; }

    .sim-thumb {
        width: 80px;
        height: 52px;
        object-fit: cover;
        border-radius: 8px;
        border: 1.5px solid #E8DDD8;
        display: block;
        cursor: pointer;
        transition: transform .15s;
    }

    .sim-thumb:hover { transform: scale(1.05); }

    .sim-placeholder {
        width: 80px;
        height: 52px;
        border-radius: 8px;
        background: #F5EDE8;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #C0A898;
        font-size: 22px;
        border: 1.5px dashed #E0D0CA;
    }

    .btn-perbesar {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: #E8622A;
        font-weight: 600;
        margin-top: 4px;
        border: none;
        background: none;
        padding: 0;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-perbesar:hover { text-decoration: underline; color: #c9521e; }

    /* ── NO TELEPON ──────────────────────────── */
    .td-telepon {
        font-size: 13px;
        color: #444;
        white-space: nowrap;
    }

    /* ── ALAMAT ──────────────────────────────── */
    .td-alamat {
        max-width: 160px;
        font-size: 13px;
        color: #444;
    }

    /* ── TOTAL BOOKING ───────────────────────── */
    .td-booking {
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        color: #1A1A1A;
    }

    /* ── AKSI ────────────────────────────────── */
    .btn-hapus-pelanggan {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        border: none;
        background: #FFE8E8;
        color: #CC2222;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .15s;
        margin: auto;
    }

    .btn-hapus-pelanggan:hover { background: #ffd0d0; }

    /* ── SCROLLBAR BAWAH (orange) ────────────── */
    .table-responsive::-webkit-scrollbar { height: 5px; }
    .table-responsive::-webkit-scrollbar-track { background: #F5EDE8; }
    .table-responsive::-webkit-scrollbar-thumb { background: #E8622A; border-radius: 10px; }

    /* ── EMPTY STATE ─────────────────────────── */
    .empty-row td {
        text-align: center;
        padding: 60px 20px;
        color: #AAA;
    }

    .empty-row i { font-size: 40px; display: block; margin-bottom: 10px; }

    /* ── MODAL FOTO SIM ──────────────────────── */
    .modal-sim-img {
        width: 100%;
        border-radius: 10px;
        object-fit: contain;
        max-height: 400px;
    }
</style>
@endpush

@section('content')

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── TABLE CARD ────────────────────────────────── --}}
<div class="pelanggan-card">
    <div class="table-responsive">
        <table class="table table-borderless table-pelanggan mb-0">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Identitas<br>(KTP/SIM)</th>
                    <th>Dokumen SIM</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th style="text-align:center;">Total<br>Booking</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pelanggan as $p)
                <tr>
                    {{-- Nama --}}
                    <td class="td-nama">{{ $p->nama }}</td>

                    {{-- Email --}}
                    <td class="td-email">{{ $p->email ?? '-' }}</td>

                    {{-- Identitas KTP & SIM --}}
                    <td class="td-identitas">
                        <div class="id-row">
                            <span class="id-label">KTP :</span>
                            <span>{{ $p->no_ktp ?? '-' }}</span>
                        </div>
                        <div class="id-row">
                            <span class="id-label">SIM :</span>
                            <span>{{ $p->no_sim ?? '-' }}</span>
                        </div>
                    </td>

                    {{-- Foto SIM --}}
                    <td class="td-sim">
                        @if($p->foto_sim)
                            <img src="{{ asset('storage/' . $p->foto_sim) }}"
                                 alt="SIM {{ $p->nama }}"
                                 class="sim-thumb"
                                 onclick="lihatSIM('{{ asset('storage/' . $p->foto_sim) }}', '{{ $p->nama }}')">
                            <button class="btn-perbesar"
                                    onclick="lihatSIM('{{ asset('storage/' . $p->foto_sim) }}', '{{ $p->nama }}')">
                                <i class="bi bi-search"></i> Perbesar
                            </button>
                        @else
                            <div class="sim-placeholder">
                                <i class="bi bi-card-image"></i>
                            </div>
                            <span style="font-size:11px;color:#bbb;display:block;margin-top:4px;">Tidak ada</span>
                        @endif
                    </td>

                    {{-- No Telepon --}}
                    <td class="td-telepon">{{ $p->no_telepon ?? '-' }}</td>

                    {{-- Alamat --}}
                    <td class="td-alamat">{{ $p->alamat ?? '-' }}</td>

                    {{-- Total Booking --}}
                    <td class="td-booking">{{ $p->transaksi_count }}</td>

                    {{-- Aksi --}}
                    <td>
                        <button class="btn-hapus-pelanggan"
                                title="Hapus"
                                onclick="konfirmasiHapus({{ $p->id_penyewa }}, '{{ addslashes($p->nama) }}')">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        <form id="form-hapus-{{ $p->id_penyewa }}"
                              action="{{ route('admin.pelanggan.destroy', $p->id_penyewa) }}"
                              method="POST" class="d-none">
                            @csrf @method('DELETE')
                        </form>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="8">
                        <i class="bi bi-people"></i>
                        <p class="mb-0 fw-600">Belum ada data pelanggan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── PAGINATION ────────────────────────────────── --}}
@if($pelanggan->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $pelanggan->appends(request()->query())->links() }}
</div>
@endif

{{-- ── MODAL LIHAT FOTO SIM ─────────────────────── --}}
<div class="modal fade" id="modalSIM" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-700" id="modal-sim-title">Foto SIM</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2 pb-4 px-4">
                <img id="modal-sim-img" src="" alt="SIM" class="modal-sim-img">
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL KONFIRMASI HAPUS ───────────────────── --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body text-center py-4 px-4">
                <div style="font-size:48px;color:#E8622A;">
                    <i class="bi bi-person-x-fill"></i>
                </div>
                <h5 class="fw-700 mt-2 mb-1">Hapus Pelanggan?</h5>
                <p class="text-muted mb-3" id="modal-hapus-nama"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger rounded-3 px-4 fw-600"
                            id="btn-konfirmasi-hapus">Ya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
/* ── Lihat foto SIM ─────────────────── */
function lihatSIM(url, nama) {
    document.getElementById('modal-sim-img').src   = url;
    document.getElementById('modal-sim-title').textContent = 'SIM – ' + nama;
    new bootstrap.Modal(document.getElementById('modalSIM')).show();
}

/* ── Konfirmasi hapus ───────────────── */
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