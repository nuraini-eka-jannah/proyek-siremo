@extends('layouts.admin')

@section('title', 'Riwayat Transaksi')
@section('page-title', 'Riwayat Transaksi')
@section('page-subtitle', 'Selamat datang di SIREMO')

@push('styles')
<style>
    /* ── TABLE CARD ──────────────────────────── */
    .transaksi-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
    }

    /* ── TABLE HEAD ──────────────────────────── */
    .table-transaksi thead tr {
        background: #FFF0EA;
    }

    .table-transaksi thead th {
        font-size: 14px;
        font-weight: 700;
        color: #1A1A1A;
        padding: 18px 20px;
        border: none;
        vertical-align: middle;
    }

    /* ── TABLE BODY ──────────────────────────── */
    .table-transaksi tbody td {
        padding: 18px 20px;
        font-size: 13.5px;
        vertical-align: middle;
        border-color: #F5EDE8;
        color: #222;
    }

    .table-transaksi tbody tr:hover { background: #FFFAF8; }

    /* ── ID TRANSAKSI ────────────────────────── */
    .td-id .id-kode {
        font-size: 15px;
        font-weight: 700;
        color: #1A1A1A;
        letter-spacing: .3px;
    }

    .td-id .id-tanggal {
        font-size: 11.5px;
        color: #AAA;
        margin-top: 2px;
    }

    /* ── PELANGGAN ───────────────────────────── */
    .td-pelanggan {
        font-weight: 600;
        font-size: 14px;
        color: #1A1A1A;
    }

    /* ── ARMADA ──────────────────────────────── */
    .td-armada .armada-nama {
        font-weight: 700;
        font-size: 13.5px;
        color: #1A1A1A;
    }

    .td-armada .armada-plat {
        font-size: 11.5px;
        color: #AAA;
        margin-top: 1px;
    }

    /* ── TOTAL TAGIHAN ───────────────────────── */
    .td-tagihan {
        font-weight: 700;
        font-size: 15px;
        color: #1A1A1A;
        white-space: nowrap;
    }

    .td-tagihan .tagihan-rp   { font-size: 12px; font-weight: 400; color: #666; }
    .td-tagihan .tagihan-nilai { font-size: 16px; font-weight: 700; }

    /* ── BUKTI PEMBAYARAN ────────────────────── */
    .td-bukti { text-align: center; }

    .bukti-thumb-wrap {
        width: 52px; height: 52px;
        border-radius: 10px;
        background: #1A6FE8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: opacity .15s;
        border: none;
    }

    .bukti-thumb-wrap:hover { opacity: .85; }
    .bukti-thumb-wrap i { color: #fff; font-size: 24px; }

    .bukti-thumb-wrap.has-img {
        background: transparent;
        border: 1.5px solid #E8DDD8;
    }

    .bukti-thumb-wrap.has-img img {
        width: 100%; height: 100%;
        object-fit: cover;
        border-radius: 9px;
    }

    .bukti-kosong {
        width: 52px; height: 52px;
        border-radius: 10px;
        background: #F5EDE8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #CCC;
        font-size: 22px;
    }

    /* ── STATUS BADGE ────────────────────────── */
    .status-pill {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-block;
        letter-spacing: .3px;
    }

    .status-menunggu { background: #FFE8D6; color: #C05000; }
    .status-lunas    { background: #D4F5E2; color: #1A7A48; }
    .status-selesai  { background: #D4F5E2; color: #1A7A48; }
    .status-aktif    { background: #D6EAFF; color: #1255A8; }
    .status-disewa   { background: #FFF0D6; color: #A86012; }
    .status-batal    { background: #FFE0DE; color: #A81212; }

    /* ── AKSI ────────────────────────────────── */
    .btn-verify {
        background: #E8622A;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background .15s;
        white-space: nowrap;
        line-height: 1.3;
        text-align: center;
    }

    .btn-verify:hover { background: #c9521e; }

    .btn-detail {
        background: #F5EDE8;
        color: #555;
        border: none;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-detail:hover { background: #E8DDD8; color: #333; }

    /* ── SCROLLBAR ORANGE ────────────────────── */
    .table-responsive::-webkit-scrollbar { height: 5px; }
    .table-responsive::-webkit-scrollbar-track { background: #F5EDE8; }
    .table-responsive::-webkit-scrollbar-thumb { background: #E8622A; border-radius: 10px; }

    /* ── FILTER BAR ──────────────────────────── */
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

    /* ── MODAL BUKTI ─────────────────────────── */
    .modal-bukti-img {
        width: 100%;
        border-radius: 10px;
        object-fit: contain;
        max-height: 460px;
    }

    /* ── EMPTY ───────────────────────────────── */
    .empty-row td {
        text-align: center;
        padding: 60px 20px;
        color: #AAA;
    }

    .empty-row i { font-size: 40px; display: block; margin-bottom: 10px; }
</style>
@endpush

@section('content')

{{-- ── FILTER BAR ────────────────────────────────── --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div class="filter-pills">
        <a href="{{ route('admin.transaksi.index') }}"
           class="pill {{ !request('status') ? 'active' : '' }}">Semua</a>
        @foreach(['Aktif','Disewa','Selesai','Batal'] as $s)
            <a href="{{ route('admin.transaksi.index', ['status' => $s]) }}"
               class="pill {{ request('status') === $s ? 'active' : '' }}">{{ $s }}</a>
        @endforeach
    </div>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── TABLE ─────────────────────────────────────── --}}
<div class="transaksi-card">
    <div class="table-responsive">
        <table class="table table-borderless table-transaksi mb-0">
            <thead>
                <tr>
                    <th>Id<br>Transaksi</th>
                    <th>Pelanggan</th>
                    <th>Armada</th>
                    <th>Total<br>Tagihan</th>
                    <th style="text-align:center;">Bukti<br>Pembayaran</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                <tr>
                    {{-- ID Transaksi --}}
                    <td class="td-id">
                        <div class="id-kode">TR - {{ $t->id_transaksi }}</div>
                        <div class="id-tanggal">
                            {{ \Carbon\Carbon::parse($t->tgl_sewa)->format('d M Y') }},
                            {{ \Carbon\Carbon::parse($t->tgl_sewa)->format('H:i') }}
                        </div>
                    </td>

                    {{-- Pelanggan --}}
                    <td class="td-pelanggan">
                        {{ $t->penyewa->nama ?? '-' }}
                    </td>

                    {{-- Armada --}}
                    <td class="td-armada">
                        <div class="armada-nama">
                            {{ $t->mobil->merek ?? '' }} {{ $t->mobil->model ?? '-' }}
                        </div>
                        <div class="armada-plat">{{ $t->mobil->plat_nomor ?? '' }}</div>
                    </td>

                    {{-- Total Tagihan --}}
                    <td class="td-tagihan">
                        <div class="tagihan-rp">Rp.</div>
                        <div class="tagihan-nilai">
                            {{ number_format($t->total_bayar + $t->denda, 0, ',', '.') }}
                        </div>
                        @if($t->denda > 0)
                            <div style="font-size:11px;color:#E8622A;margin-top:2px;">
                                <i class="bi bi-exclamation-circle"></i>
                                Denda: Rp {{ number_format($t->denda, 0, ',', '.') }}
                            </div>
                        @endif
                    </td>

                    {{-- Bukti Pembayaran --}}
                    <td class="td-bukti">
                        @if($t->bukti_pembayaran)
                            @php
                                // Cek apakah isian adalah path file atau kode bukti
                                $isFoto = str_contains($t->bukti_pembayaran, '.') ||
                                          str_contains($t->bukti_pembayaran, '/');
                            @endphp
                            @if($isFoto)
                                <button class="bukti-thumb-wrap has-img"
                                        onclick="lihatBukti('{{ asset('storage/' . $t->bukti_pembayaran) }}', 'TR-{{ $t->id_transaksi }}')">
                                    <img src="{{ asset('storage/' . $t->bukti_pembayaran) }}" alt="Bukti">
                                </button>
                            @else
                                {{-- Kode bukti teks (misal BUKTI_10_xxx) --}}
                                <button class="bukti-thumb-wrap"
                                        title="{{ $t->bukti_pembayaran }}"
                                        onclick="alert('Kode Bukti: {{ $t->bukti_pembayaran }}')">
                                    <i class="bi bi-card-image"></i>
                                </button>
                            @endif
                        @else
                            <div class="bukti-kosong">
                                <i class="bi bi-image"></i>
                            </div>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        @php
                            $statusMap = [
                                'Selesai' => ['pill' => 'status-selesai',  'label' => 'LUNAS'],
                                'Aktif'   => ['pill' => 'status-aktif',    'label' => 'AKTIF'],
                                'Disewa'  => ['pill' => 'status-disewa',   'label' => 'DISEWA'],
                                'Batal'   => ['pill' => 'status-batal',    'label' => 'BATAL'],
                            ];
                            $st = $statusMap[$t->status_transaksi]
                                ?? ['pill' => 'status-menunggu', 'label' => 'MENUNGGU'];
                        @endphp
                        <span class="status-pill {{ $st['pill'] }}">{{ $st['label'] }}</span>
                    </td>

                    {{-- Aksi --}}
                    <td style="text-align:center;">
                        <div class="d-flex gap-2 justify-content-center align-items-center flex-wrap">

                            {{-- Tombol Verify Payment: hanya jika status belum Selesai/Batal --}}
                            @if(!in_array($t->status_transaksi, ['Selesai', 'Batal']))
                                <form action="{{ route('admin.transaksi.selesai', $t->id_transaksi) }}"
                                      method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-verify"
                                            onclick="return confirm('Tandai transaksi ini sebagai Selesai/Lunas?')">
                                        Verify<br>Payment
                                    </button>
                                </form>
                            @endif

                            {{-- Tombol Detail --}}
                            <a href="{{ route('admin.transaksi.show', $t->id_transaksi) }}"
                               class="btn-detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- Tombol Hapus --}}
                            <button class="btn-detail"
                                    style="color:#CC2222;background:#FFE8E8;"
                                    onclick="konfirmasiHapus({{ $t->id_transaksi }})">
                                <i class="bi bi-trash-fill"></i>
                            </button>
                            <form id="form-hapus-{{ $t->id_transaksi }}"
                                  action="{{ route('admin.transaksi.destroy', $t->id_transaksi) }}"
                                  method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="7">
                        <i class="bi bi-receipt"></i>
                        <p class="mb-0 fw-600">Belum ada data transaksi.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── PAGINATION ────────────────────────────────── --}}
@if($transaksi->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $transaksi->appends(request()->query())->links() }}
    </div>
@endif

{{-- ── MODAL BUKTI PEMBAYARAN ──────────────────── --}}
<div class="modal fade" id="modalBukti" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-700" id="modal-bukti-title">Bukti Pembayaran</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2 pb-4 px-4">
                <img id="modal-bukti-img" src="" alt="Bukti" class="modal-bukti-img">
            </div>
        </div>
    </div>
</div>

{{-- ── MODAL HAPUS ──────────────────────────────── --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-body text-center py-4 px-4">
                <div style="font-size:48px;color:#E8622A;">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <h5 class="fw-700 mt-2 mb-1">Hapus Transaksi?</h5>
                <p class="text-muted mb-3">Data transaksi akan dihapus permanen.</p>
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
/* ── Lihat bukti pembayaran ─────────── */
function lihatBukti(url, kode) {
    document.getElementById('modal-bukti-img').src = url;
    document.getElementById('modal-bukti-title').textContent = 'Bukti – ' + kode;
    new bootstrap.Modal(document.getElementById('modalBukti')).show();
}

/* ── Konfirmasi hapus ───────────────── */
let targetFormId = null;

function konfirmasiHapus(id) {
    targetFormId = 'form-hapus-' + id;
    new bootstrap.Modal(document.getElementById('modalHapus')).show();
}

document.getElementById('btn-konfirmasi-hapus').addEventListener('click', function () {
    if (targetFormId) document.getElementById(targetFormId).submit();
});
</script>
@endpush