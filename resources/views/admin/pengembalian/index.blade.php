@extends('layouts.admin')

@section('title', 'Pengembalian & Denda')
@section('page-title', 'Pengembalian & Denda')
@section('page-subtitle', 'Selamat datang di SIREMO')

@push('styles')
<style>
    /* ── FIX PAGINATION ICON ─────────────────── */
    .pagination svg {
        width: 16px !important;
        height: 16px !important;
    }
    .pagination .flex {
        display: inline-flex;
        align-items: center;
    }

    /* ── TABLE CARD ──────────────────────────── */
    .pengembalian-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
    }

    /* ── TABLE HEAD ──────────────────────────── */
    .table-pengembalian thead tr {
        background: #FFF0EA;
    }

    .table-pengembalian thead th {
        font-size: 15px;
        font-weight: 700;
        color: #1A1A1A;
        padding: 20px 24px;
        border: none;
        vertical-align: middle;
    }

    /* ── TABLE BODY ──────────────────────────── */
    .table-pengembalian tbody td {
        padding: 22px 24px;
        font-size: 14px;
        vertical-align: middle;
        border-color: #F5EDE8;
        color: #222;
    }

    .table-pengembalian tbody tr:hover { background: #FFFAF8; }

    /* ── ID KENDARAAN ────────────────────────── */
    .td-id {
        font-size: 16px;
        font-weight: 700;
        color: #1A1A1A;
        letter-spacing: .5px;
    }

    /* ── ARMADA ──────────────────────────────── */
    .td-armada .armada-nama {
        font-weight: 700;
        font-size: 14px;
        color: #1A1A1A;
    }

    .td-armada .armada-plat {
        font-size: 11.5px;
        color: #AAA;
        margin-top: 2px;
    }

    /* ── PELANGGAN ───────────────────────────── */
    .td-pelanggan {
        font-size: 15px;
        font-weight: 500;
        color: #1A1A1A;
    }

    /* ── TANGGAL ─────────────────────────────── */
    .td-tanggal {
        font-size: 14px;
        color: #333;
        white-space: nowrap;
    }

    /* ── STATUS BADGE ────────────────────────── */
    .status-pill {
        padding: 7px 18px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        letter-spacing: .4px;
    }

    .pill-kembali   { background: #D4F5E2; color: #1A7A48; }
    .pill-tertunda { background: #FFE8D6; color: #C05000; }
    .pill-selesai  { background: #D6EAFF; color: #1255A8; }
    .pill-proses   { background: #EDE6FF; color: #6320CC; }

    /* ── AKSI ────────────────────────────────── */
    .td-aksi { min-width: 200px; }

    .btn-input-denda {
        background: #FFF0D6;
        color: #A86012;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background .15s;
        white-space: nowrap;
    }

    .btn-input-denda:hover { background: #FFE0A0; }

    .btn-selesai {
        background: #2A6B3C;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 18px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        transition: background .15s;
        white-space: nowrap;
    }

    .btn-selesai:hover { background: #1E5030; }

    /* ── SCROLLBAR ORANGE ────────────────────── */
    .table-responsive::-webkit-scrollbar { height: 5px; }
    .table-responsive::-webkit-scrollbar-track { background: #F5EDE8; }
    .table-responsive::-webkit-scrollbar-thumb { background: #E8622A; border-radius: 10px; }

    /* ── MODAL DENDA ─────────────────────────── */
    .modal-label {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }

    .modal-input {
        border-radius: 10px;
        border: 1.5px solid #E8DDD8;
        font-size: 13.5px;
        padding: 10px 14px;
        width: 100%;
        transition: border-color .15s, box-shadow .15s;
    }

    .modal-input:focus {
        outline: none;
        border-color: #E8622A;
        box-shadow: 0 0 0 3px rgba(232,98,42,.12);
    }

    .btn-simpan-denda {
        background: #E8622A;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        width: 100%;
        transition: background .15s;
    }

    .btn-simpan-denda:hover { background: #c9521e; }

    /* ── EMPTY ───────────────────────────────── */
    .empty-row td {
        text-align: center;
        padding: 60px 20px;
        color: #AAA;
    }

    .empty-row i { font-size: 40px; display: block; margin-bottom: 10px; }

    /* ── INFO CHIP ───────────────────────────── */
    .info-chips {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .info-chip {
        background: #fff;
        border-radius: 10px;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 160px;
    }

    .chip-icon {
        width: 36px; height: 36px;
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
    }

    .chip-label { font-size: 11px; color: #999; font-weight: 600; }
    .chip-value { font-size: 18px; font-weight: 700; color: #1A1A1A; }
</style>
@endpush

@section('content')

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── STAT CHIPS ────────────────────────────────── --}}
<div class="info-chips mb-4">
    <div class="info-chip">
        <div class="chip-icon" style="background:#FFF0EA;color:#E8622A;">
            <i class="bi bi-arrow-return-left"></i>
        </div>
        <div>
            <div class="chip-label">Total Aktif</div>
            <div class="chip-value">{{ $totalAktif }}</div>
        </div>
    </div>
    <div class="info-chip">
        <div class="chip-icon" style="background:#FFE8D6;color:#C05000;">
            <i class="bi bi-clock-history"></i>
        </div>
        <div>
            <div class="chip-label">Terlambat</div>
            <div class="chip-value">{{ $totalTerlambat }}</div>
        </div>
    </div>
    <div class="info-chip">
        <div class="chip-icon" style="background:#D4F5E2;color:#1A7A48;">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <div>
            <div class="chip-label">Selesai Hari Ini</div>
            <div class="chip-value">{{ $selesaiHariIni }}</div>
        </div>
    </div>
</div>

{{-- ── TABLE CARD ────────────────────────────────── --}}
<div class="pengembalian-card">
    <div class="table-responsive">
        <table class="table table-borderless table-pengembalian mb-0">
            <thead>
                <tr>
                    <th>Id<br>Kendaraan</th>
                    <th>Armada</th>
                    <th>Pelanggan</th>
                    <th>Tanggal<br>Kembali</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                @php
                    $kodeRN = '#RN'
                        . strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $t->mobil->merek ?? 'XX'), 0, 2))
                        . str_pad($t->id_transaksi, 3, '0', STR_PAD_LEFT);

                    $tglRencana  = \Carbon\Carbon::parse($t->tgl_rencana_kembali);
                    $tglHariIni  = \Carbon\Carbon::today();
                    $terlambat   = $tglHariIni->gt($tglRencana);
                    $selisihHari = $terlambat ? $tglHariIni->diffInDays($tglRencana) : 0;
                @endphp
                <tr>
                    <td class="td-id">{{ $kodeRN }}</td>
                    <td class="td-armada">
                        <div class="armada-nama">
                            {{ $t->mobil->merek ?? '' }} {{ $t->mobil->model ?? '-' }}
                        </div>
                        <div class="armada-plat">{{ $t->mobil->plat_nomor ?? '' }}</div>
                    </td>
                    <td class="td-pelanggan">{{ $t->penyewa->nama ?? '-' }}</td>
                    <td class="td-tanggal">
                        {{ \Carbon\Carbon::parse($t->tgl_rencana_kembali)->format('d M Y, H:i') }}
                        @if($terlambat)
                            <div style="font-size:11px;color:#CC2222;margin-top:3px;">
                                <i class="bi bi-exclamation-circle"></i>
                                Terlambat {{ $selisihHari }} hari
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($t->status_transaksi === 'Selesai')
                            <span class="status-pill pill-selesai">SELESAI</span>
                        @elseif($terlambat)
                            <span class="status-pill pill-tertunda">TERTUNDA</span>
                        @else
                            <span class="status-pill pill-kembali">KEMBALI</span>
                        @endif
                    </td>
                    <td class="td-aksi">
                        <div class="d-flex gap-2 align-items-center justify-content-center flex-wrap">
                            @if($t->status_transaksi !== 'Selesai')
                                @if($terlambat || $t->denda > 0)
                                    <button class="btn-input-denda"
                                            onclick="bukaModalDenda(
                                                {{ $t->id_transaksi }},
                                                '{{ addslashes($t->penyewa->nama ?? '') }}',
                                                {{ $selisihHari }},
                                                {{ $t->mobil->tarif_sewa_per_hari ?? 0 }},
                                                {{ $t->denda }},
                                                '{{ addslashes($t->ulasan_denda ?? '') }}'
                                            )">
                                        Input Denda
                                    </button>
                                @endif

                                <form action="{{ route('admin.pengembalian.proses', $t->id_transaksi) }}"
                                      method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="tgl_aktual_kembali"
                                           value="{{ now()->format('Y-m-d') }}">
                                    <input type="hidden" name="denda" value="{{ $t->denda }}">
                                    <input type="hidden" name="ulasan_denda" value="{{ $t->ulasan_denda }}">
                                    <button type="submit" class="btn-selesai"
                                            onclick="return confirm('Proses pengembalian kendaraan ini sekarang?')">
                                        Selesai
                                    </button>
                                </form>
                            @else
                                <span style="font-size:12px;color:#AAA;">— Sudah selesai —</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="6">
                        <i class="bi bi-arrow-return-left"></i>
                        <p class="mb-0 fw-600">Tidak ada data pengembalian aktif.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if($transaksi->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $transaksi->links() }}
    </div>
@endif

{{-- ── MODAL INPUT DENDA ────────────────────────── --}}
<div class="modal fade" id="modalDenda" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div>
                    <h5 class="fw-700 mb-0">Input Denda</h5>
                    <p class="text-muted mb-0" style="font-size:13px;" id="modal-denda-nama"></p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pb-4 pt-3">
                <div id="info-terlambat"
                     style="background:#FFF0EA;border-radius:10px;padding:12px 16px;
                            margin-bottom:16px;font-size:13px;">
                    <i class="bi bi-clock-history me-1" style="color:#E8622A;"></i>
                    <span id="info-terlambat-teks"></span>
                </div>

                <form id="form-denda" method="POST" action="">
                    @csrf

                    <div class="mb-3">
                        <label class="modal-label">Tanggal Aktual Kembali</label>
                        <input type="date" name="tgl_aktual_kembali" class="modal-input"
                               value="{{ now()->format('Y-m-d') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="modal-label">Jumlah Denda (Rp)</label>
                        <input type="number" name="denda" id="input-denda"
                               class="modal-input" min="0" placeholder="0">
                        <div style="font-size:11px;color:#999;margin-top:4px;">
                            <i class="bi bi-info-circle"></i>
                            Estimasi otomatis: <span id="estimasi-denda" class="fw-600"></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="modal-label">Keterangan Denda</label>
                        <textarea name="ulasan_denda" class="modal-input" rows="2"
                                  id="input-ulasan-denda"
                                  placeholder="Contoh: terlambat 2 hari, ada goresan, dll"></textarea>
                    </div>

                    <button type="submit" class="btn-simpan-denda">
                        <i class="bi bi-floppy-fill me-1"></i> Simpan & Proses Pengembalian
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function bukaModalDenda(idTransaksi, namaPenyewa, selisihHari, tarifPerHari, dendaSebelumnya, ulasanSebelumnya) {
    // FIX: Menggunakan base URL dinamis dari Laravel agar aman dari sub-folder proyek
    document.getElementById('form-denda').action = "{{ url('admin/pengembalian') }}/" + idTransaksi;

    document.getElementById('modal-denda-nama').textContent = namaPenyewa;

    const estimasi = selisihHari * tarifPerHari;
    document.getElementById('estimasi-denda').textContent =
        'Rp ' + estimasi.toLocaleString('id-ID');

    if (selisihHari > 0) {
        document.getElementById('info-terlambat-teks').textContent =
            'Terlambat ' + selisihHari + ' hari × Rp ' +
            tarifPerHari.toLocaleString('id-ID') + '/hari';
        document.getElementById('info-terlambat').style.display = 'block';
    } else {
        document.getElementById('info-terlambat').style.display = 'none';
    }

    document.getElementById('input-denda').value =
        dendaSebelumnya > 0 ? dendaSebelumnya : (estimasi > 0 ? estimasi : '');

    document.getElementById('input-ulasan-denda').value = ulasanSebelumnya || '';

    new bootstrap.Modal(document.getElementById('modalDenda')).show();
}
</script>
@endpush