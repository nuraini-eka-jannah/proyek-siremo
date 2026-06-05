@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang di SIREMO')

@push('styles')
<style>
/* ── STAT CARD LIVE INDICATOR ─── */
.stat-card { position: relative; overflow: visible; }

.live-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #2DCE89;
    display: inline-block;
    margin-right: 4px;
    animation: pulse-dot 2s infinite;
}

@keyframes pulse-dot {
    0%,100% { opacity: 1; transform: scale(1); }
    50%      { opacity: .5; transform: scale(1.3); }
}

.stat-sub {
    font-size: 11px;
    color: #999;
    margin-top: 2px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-sub .tambahan {
    color: #2DCE89;
    font-weight: 700;
}

/* ── NOTIF DROPDOWN ──────────────── */
.notif-dropdown {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 360px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0,0,0,.15);
    z-index: 9999;
    display: none;
    overflow: hidden;
}

.notif-dropdown.show { display: block; }

.notif-header {
    padding: 16px 18px 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #F5EDE8;
}

.notif-header h6 { margin: 0; font-size: 15px; font-weight: 700; }

.btn-baca-semua {
    font-size: 12px;
    color: #E8622A;
    font-weight: 600;
    border: none;
    background: none;
    cursor: pointer;
    padding: 0;
}

.btn-baca-semua:hover { text-decoration: underline; }

.notif-list { max-height: 360px; overflow-y: auto; }

.notif-list::-webkit-scrollbar { width: 4px; }
.notif-list::-webkit-scrollbar-thumb { background: #F0E8E3; border-radius: 4px; }

.notif-item {
    display: flex;
    gap: 12px;
    padding: 13px 18px;
    border-bottom: 1px solid #FAF5F2;
    cursor: pointer;
    transition: background .12s;
    text-decoration: none;
}

.notif-item:hover { background: #FAF5F2; }
.notif-item.belum-baca { background: #FFF8F5; }

.notif-icon-wrap {
    width: 40px; height: 40px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.notif-judul {
    font-size: 13px;
    font-weight: 700;
    color: #1A1A1A;
    line-height: 1.3;
}

.notif-pesan {
    font-size: 12px;
    color: #777;
    margin-top: 2px;
    line-height: 1.4;
}

.notif-waktu {
    font-size: 11px;
    color: #BBB;
    margin-top: 4px;
}

.notif-unread-dot {
    width: 8px; height: 8px;
    background: #E8622A;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 6px;
}

.notif-empty {
    text-align: center;
    padding: 36px 20px;
    color: #CCC;
}

.notif-empty i { font-size: 36px; display: block; margin-bottom: 10px; }

.notif-footer {
    padding: 12px 18px;
    text-align: center;
    border-top: 1px solid #F5EDE8;
}

.notif-footer a {
    font-size: 13px;
    color: #E8622A;
    font-weight: 600;
    text-decoration: none;
}
</style>
@endpush

@section('content')

{{-- ── CETAK LAPORAN ──────────────────────────── --}}
<div class="mb-4">
    <a href="{{ route('admin.laporan.cetak') }}"
       class="btn btn-sm px-3"
       style="background:#E8622A;border:none;color:#fff;border-radius:8px;
              font-size:13px;font-weight:600;padding:10px 18px;display:inline-flex;
              align-items:center;gap:6px;">
        <i class="bi bi-printer"></i> Cetak Laporan (PDF)
    </a>
</div>

{{-- ── STAT CARDS ─────────────────────────────── --}}
<div class="row g-3 mb-4">

    {{-- CARD 1: Pendapatan --}}
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            @php
                $badgePct = ($pctPendapatan >= 0 ? '+' : '') . $pctPendapatan . '%';
                $badgeBg  = $pctPendapatan >= 0 ? '#D4F5E2' : '#FFE0DE';
                $badgeClr = $pctPendapatan >= 0 ? '#1A7A48' : '#A81212';
            @endphp
            <span class="stat-badge" id="badge-pendapatan"
                  style="background:{{ $badgeBg }};color:{{ $badgeClr }};">
                {{ $badgePct }}
            </span>
            <div class="stat-icon" style="background:#FFF0EA;color:#E8622A;">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <div class="stat-value" id="val-pendapatan">
                Rp {{ number_format($pendapatanBulanIni / 1000000, 1) }}M
            </div>
            <div class="stat-label">Pendapatan Bulan Ini</div>
            <div class="stat-sub">
                <span class="live-dot"></span>
                Hari ini:
                <span class="tambahan" id="sub-pendapatan">
                    +Rp {{ number_format($pendapatanHariIni / 1000, 0) }}K
                </span>
            </div>
        </div>
    </div>

    {{-- CARD 2: Armada --}}
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <span class="stat-badge" id="badge-armada"
                  style="background:#D6EAFF;color:#1255A8;">
                {{ $totalMobil }} unit
            </span>
            <div class="stat-icon" style="background:#EBF5FF;color:#1255A8;">
                <i class="bi bi-car-front"></i>
            </div>
            <div class="stat-value" id="val-armada">{{ $armadaTersedia }}</div>
            <div class="stat-label">Armada Tersedia</div>
            <div class="stat-sub">
                <span class="live-dot"></span>
                Baru hari ini:
                <span class="tambahan" id="sub-armada">
                    +{{ $armadaBaruHariIni }}
                </span>
            </div>
        </div>
    </div>

    {{-- CARD 3: Penyewaan Aktif --}}
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <span class="stat-badge" id="badge-penyewaan"
                  style="background:#EDE6FF;color:#6320CC;">
                Aktif
            </span>
            <div class="stat-icon" style="background:#EDE6FF;color:#6320CC;">
                <i class="bi bi-key"></i>
            </div>
            <div class="stat-value" id="val-penyewaan">{{ $penyewaanAktif }}</div>
            <div class="stat-label">Penyewaan Aktif</div>
            <div class="stat-sub">
                <span class="live-dot"></span>
                Baru hari ini:
                <span class="tambahan" id="sub-penyewaan">
                    +{{ $penyewaanBaruHariIni }}
                </span>
            </div>
        </div>
    </div>

    {{-- CARD 4: Total Pelanggan --}}
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <span class="stat-badge" id="badge-pelanggan"
                  style="background:#FFE8F5;color:#A8128A;">
                +{{ $pelangganBaruHariIni }}
            </span>
            <div class="stat-icon" style="background:#FFE8F5;color:#A8128A;">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-value" id="val-pelanggan">
                {{ number_format($totalPelanggan) }}
            </div>
            <div class="stat-label">Total Pelanggan</div>
            <div class="stat-sub">
                <span class="live-dot"></span>
                Baru hari ini:
                <span class="tambahan" id="sub-pelanggan">
                    +{{ $pelangganBaruHariIni }}
                </span>
            </div>
        </div>
    </div>

</div>

{{-- ── CHARTS ──────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="chart-card">
            <h6>Pendapatan Bulanan</h6>
            <canvas id="chartPendapatan" height="100"></canvas>
        </div>
    </div>
    <div class="col-md-4">
        <div class="chart-card h-100">
            <h6>Status Kendaraan</h6>
            <canvas id="chartStatus"></canvas>
        </div>
    </div>
</div>

{{-- ── TABEL PENYEWA TERBARU ───────────────────── --}}
<div class="table-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Penyewa Terbaru</h6>
        <a href="{{ route('admin.transaksi.index') }}"
           style="background:var(--primary-soft);color:var(--primary);border:none;
                  border-radius:8px;font-size:12px;font-weight:600;padding:6px 14px;
                  text-decoration:none;">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-borderless align-middle">
            <thead>
                <tr>
                    <th>No</th><th>Penyewa</th><th>Unit</th>
                    <th>Durasi Sewa</th><th>Status</th><th>Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penyewaTerbaru as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-600">{{ $t->penyewa->nama ?? '-' }}</td>
                    <td>
                        <div style="font-size:13px;font-weight:600;">
                            {{ $t->mobil->merek ?? '' }} {{ $t->mobil->model ?? '-' }}
                        </div>
                        <div style="font-size:11px;color:var(--muted);">
                            {{ $t->mobil->plat_nomor ?? '' }}
                        </div>
                    </td>
                    <td>
                        <div>{{ $t->tgl_sewa?->format('d M Y') }} –</div>
                        <div>{{ $t->tgl_rencana_kembali?->format('d M Y') }}</div>
                    </td>
                    <td>
                        @php
                            $cls = match($t->status_transaksi) {
                                'Selesai' => 'status-selesai',
                                'Aktif'   => 'status-aktif',
                                'Batal'   => 'status-batal',
                                'Disewa'  => 'status-disewa',
                                default   => 'status-aktif',
                            };
                        @endphp
                        <span class="status-badge {{ $cls }}">
                            {{ strtoupper($t->status_transaksi) }}
                        </span>
                    </td>
                    <td class="fw-600">
                        Rp {{ number_format($t->total_bayar + $t->denda, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── NOTIFIKASI DROPDOWN (dipasang di sini, dikontrol dari topbar) ── --}}
<div class="notif-dropdown" id="notifDropdown">
    <div class="notif-header">
        <h6><i class="bi bi-bell-fill me-2" style="color:#E8622A;"></i>Notifikasi</h6>
        <button class="btn-baca-semua" onclick="bacaSemuaNotif()">Tandai semua dibaca</button>
    </div>

    <div class="notif-list" id="notifList">
        @forelse($notifikasi as $n)
        <a href="{{ $n->url ?? '#' }}"
           class="notif-item {{ !$n->dibaca ? 'belum-baca' : '' }}"
           onclick="bacaNotif({{ $n->id_notifikasi }})">
            <div class="notif-icon-wrap"
                 style="background:{{ $n->warna }}22;color:{{ $n->warna }};">
                <i class="bi {{ $n->icon }}"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div class="notif-judul">{{ $n->judul }}</div>
                <div class="notif-pesan">{{ $n->pesan }}</div>
                <div class="notif-waktu">
                    {{ $n->created_at->diffForHumans() }}
                </div>
            </div>
            @if(!$n->dibaca)
                <div class="notif-unread-dot"></div>
            @endif
        </a>
        @empty
        <div class="notif-empty">
            <i class="bi bi-bell-slash"></i>
            <p style="font-size:13px;">Belum ada notifikasi</p>
        </div>
        @endforelse
    </div>

    <div class="notif-footer">
        <a href="{{ route('admin.transaksi.index') }}">Lihat semua transaksi →</a>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Line Chart ─────────────────────────────────────────────────
new Chart(document.getElementById('chartPendapatan').getContext('2d'), {
    type: 'line',
    data: {
        labels: {!! json_encode($bulanLabels) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($bulanData) !!},
            borderColor: '#E8622A',
            backgroundColor: 'rgba(232,98,42,.08)',
            borderWidth: 2.5,
            pointBackgroundColor: '#E8622A',
            pointRadius: 5,
            tension: 0.35,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID') } }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#F0E8E3' },
                ticks: { callback: v => (v/1000000).toFixed(0)+'Jt', font: { size: 11 } }
            },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});

// ── Donut Chart ────────────────────────────────────────────────
new Chart(document.getElementById('chartStatus').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Tersedia','Disewa','Perawatan'],
        datasets: [{
            data: [{{ $tersedia }},{{ $disewa }},{{ $perawatan }}],
            backgroundColor: ['#2DCE89','#5B9CF6','#FFC107'],
            borderWidth: 0, hoverOffset: 6,
        }]
    },
    options: {
        responsive: true, cutout: '65%',
        plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 12 } } } }
    }
});

// ══════════════════════════════════════════════════════════════
// NOTIFIKASI
// ══════════════════════════════════════════════════════════════
const notifBtn      = document.getElementById('notifBtn');
const notifDropdown = document.getElementById('notifDropdown');

// Posisikan dropdown relatif ke tombol notif
notifBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    notifDropdown.classList.toggle('show');

    // Posisi dropdown mengikuti tombol
    const rect = notifBtn.getBoundingClientRect();
    notifDropdown.style.position = 'fixed';
    notifDropdown.style.top      = (rect.bottom + 10) + 'px';
    notifDropdown.style.right    = (window.innerWidth - rect.right) + 'px';
});

// Tutup dropdown saat klik di luar
document.addEventListener('click', function() {
    notifDropdown.classList.remove('show');
});

notifDropdown.addEventListener('click', e => e.stopPropagation());

// Baca semua notifikasi
function bacaSemuaNotif() {
    fetch('{{ route("admin.notifikasi.baca-semua") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    }).then(() => {
        document.querySelectorAll('.notif-item.belum-baca').forEach(el => {
            el.classList.remove('belum-baca');
        });
        document.querySelectorAll('.notif-unread-dot').forEach(el => el.remove());
        updateBadgeNotif(0);
    });
}

function updateBadgeNotif(jumlah) {
    const badge = document.getElementById('notifBadge');
    if (jumlah > 0) {
        badge.textContent = jumlah > 9 ? '9+' : jumlah;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

// ══════════════════════════════════════════════════════════════
// POLLING: Update stat card & notif otomatis tiap 30 detik
// ══════════════════════════════════════════════════════════════
function pollStatTerbaru() {
    fetch('{{ route("admin.stat-terbaru") }}')
        .then(r => r.json())
        .then(d => {
            // Pendapatan
            const juta = d.pendapatan_bulan_ini / 1000000;
            document.getElementById('val-pendapatan').textContent =
                'Rp ' + juta.toFixed(1) + 'M';
            document.getElementById('sub-pendapatan').textContent =
                '+Rp ' + Math.round(d.pendapatan_hari_ini / 1000) + 'K';
            const pct = d.pct_pendapatan;
            const badgePct = document.getElementById('badge-pendapatan');
            badgePct.textContent = (pct >= 0 ? '+' : '') + pct + '%';
            badgePct.style.background = pct >= 0 ? '#D4F5E2' : '#FFE0DE';
            badgePct.style.color      = pct >= 0 ? '#1A7A48' : '#A81212';

            // Armada
            document.getElementById('val-armada').textContent   = d.armada_tersedia;
            document.getElementById('badge-armada').textContent = d.total_mobil + ' unit';
            document.getElementById('sub-armada').textContent   = '+' + d.armada_baru_hari_ini;

            // Penyewaan
            document.getElementById('val-penyewaan').textContent = d.penyewaan_aktif;
            document.getElementById('sub-penyewaan').textContent = '+' + d.penyewaan_baru_hari_ini;

            // Pelanggan
            document.getElementById('val-pelanggan').textContent     = d.total_pelanggan.toLocaleString('id-ID');
            document.getElementById('badge-pelanggan').textContent   = '+' + d.pelanggan_baru_hari_ini;
            document.getElementById('sub-pelanggan').textContent     = '+' + d.pelanggan_baru_hari_ini;
        })
        .catch(() => {}); // silent fail
}

function pollNotifikasi() {
    fetch('{{ route("admin.notifikasi.terbaru") }}')
        .then(r => r.json())
        .then(d => {
            updateBadgeNotif(d.belum_baca);

            // Update list notif jika dropdown sedang tertutup
            if (!notifDropdown.classList.contains('show') && d.notifikasi.length > 0) {
                let html = '';
                d.notifikasi.forEach(n => {
                    html += `
                    <a href="${n.url || '#'}"
                       class="notif-item ${!n.dibaca ? 'belum-baca' : ''}"
                       onclick="bacaNotif(${n.id_notifikasi})">
                        <div class="notif-icon-wrap"
                             style="background:${n.warna}22;color:${n.warna};">
                            <i class="bi ${n.icon}"></i>
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div class="notif-judul">${n.judul}</div>
                            <div class="notif-pesan">${n.pesan || ''}</div>
                            <div class="notif-waktu">${n.created_at_diff || ''}</div>
                        </div>
                        ${!n.dibaca ? '<div class="notif-unread-dot"></div>' : ''}
                    </a>`;
                });
                document.getElementById('notifList').innerHTML = html || '<div class="notif-empty"><i class="bi bi-bell-slash"></i><p>Belum ada notifikasi</p></div>';
            }
        })
        .catch(() => {});
}

// Jalankan polling
setInterval(pollStatTerbaru,  60000); // stat card tiap 60 detik
setInterval(pollNotifikasi,   30000); // notif tiap 30 detik
</script>
@endpush