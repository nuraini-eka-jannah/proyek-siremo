<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIREMO – @yield('title', 'Dashboard')</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary:      #E8622A;
            --primary-soft: #FFF0EA;
            --sidebar-w:    220px;
            --topbar-h:     64px;
            --bg:           #F5EDE8;
            --card-bg:      #FFFFFF;
            --text:         #1A1A1A;
            --muted:        #7A7A7A;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
        }

        /* ── SIDEBAR ─────────────────────────────── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: #fff;
            display: flex;
            flex-direction: column;
            z-index: 100;
            border-right: 1px solid #EEE;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 18px 20px;
            border-bottom: 1px solid #F0E8E3;
        }

        .brand-icon {
            width: 38px; height: 38px;
            background: var(--primary);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 18px;
        }

        .brand-text h6 {
            margin: 0;
            font-weight: 700;
            font-size: 15px;
            color: var(--primary);
        }

        .brand-text small {
            font-size: 10px;
            color: var(--muted);
        }

        .sidebar-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--muted);
            padding: 16px 20px 6px;
        }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 0 12px; }

        .nav-link-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #555;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: background .15s, color .15s;
            margin-bottom: 2px;
        }

        .nav-link-item i { font-size: 16px; width: 20px; text-align: center; }

        .nav-link-item:hover { background: var(--primary-soft); color: var(--primary); }

        .nav-link-item.active {
            background: var(--primary-soft);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid #F0E8E3;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border-radius: 8px;
            background: var(--primary-soft);
        }

        .user-avatar {
            width: 34px; height: 34px;
            background: var(--primary);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .user-info small { font-size: 11px; color: var(--muted); display: block; overflow: hidden; text-overflow: ellipsis; max-width: 110px; white-space: nowrap; }
        .user-info span  { font-size: 12.5px; font-weight: 600; display: block; overflow: hidden; text-overflow: ellipsis; max-width: 110px; white-space: nowrap; }

        .btn-edit-profil {
            display: block;
            text-align: center;
            margin-top: 8px;
            padding: 6px;
            border-radius: 6px;
            background: #fff;
            border: 1px solid #E0D0CA;
            font-size: 12px;
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: background .15s;
            width: 100%;
        }

        .btn-edit-profil:hover { background: var(--primary-soft); color: var(--primary); }

        /* ── TOPBAR ──────────────────────────────── */
        #topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-w);
            right: 0;
            height: var(--topbar-h);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            z-index: 99;
            border-bottom: 1px solid #EEE;
        }

        .topbar-title h5 { margin: 0; font-weight: 700; font-size: 18px; }
        .topbar-title p  { margin: 0; font-size: 12px; color: var(--muted); }

        .topbar-right { display: flex; align-items: center; gap: 14px; }

        .search-box {
            display: flex;
            align-items: center;
            background: #F5F5F5;
            border-radius: 10px;
            padding: 8px 14px;
            gap: 8px;
            width: 220px;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 13px;
            width: 100%;
        }

        .notif-btn {
            position: relative;
            background: #F5F5F5;
            border: none;
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            cursor: pointer;
        }

        .notif-badge {
            position: absolute;
            top: 4px; right: 4px;
            background: var(--primary);
            color: #fff;
            font-size: 9px;
            width: 16px; height: 16px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }

        /* ── MAIN CONTENT ────────────────────────── */
        #main-content {
            margin-left: var(--sidebar-w);
            padding-top: var(--topbar-h);
            min-height: 100vh;
        }

        .content-body { padding: 24px 28px; }

        /* ── CARDS ───────────────────────────────── */
        .stat-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 18px 20px;
            position: relative;
            overflow: hidden;
        }

        .stat-badge {
            position: absolute;
            top: 12px; right: 12px;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .stat-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .stat-value { font-size: 22px; font-weight: 700; margin-bottom: 2px; }
        .stat-label { font-size: 12px; color: var(--muted); }

        /* ── CHART CARDS ─────────────────────────── */
        .chart-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 22px 24px;
        }

        .chart-card h6 { font-weight: 700; font-size: 16px; margin-bottom: 16px; }

        /* ── TABLE ───────────────────────────────── */
        .table-card {
            background: var(--card-bg);
            border-radius: 14px;
            padding: 22px 24px;
        }

        .table-card h6 { font-weight: 700; font-size: 16px; margin-bottom: 16px; }

        .table thead th {
            background: #FFF0EA;
            font-size: 13px;
            font-weight: 600;
            color: #555;
            border: none;
            padding: 12px 14px;
        }

        .table tbody td {
            font-size: 13px;
            padding: 11px 14px;
            vertical-align: middle;
            border-color: #F5EDE8;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-selesai  { background: #D4F5E2; color: #1A7A48; }
        .status-aktif    { background: #D6EAFF; color: #1255A8; }
        .status-batal    { background: #FFE0DE; color: #A81212; }
        .status-disewa   { background: #FFF0D6; color: #A86012; }

        /* ── STYLING MODAL EDIT PROFIL ── */
        .custom-profile-modal {
            border-radius: 24px !important;
            padding: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .custom-input {
            border-radius: 12px !important;
            padding: 10px 14px;
            font-size: 0.9rem;
            border: 1.5px solid #e5e7eb;
        }
        .custom-input:focus {
            border-color: #E8622A;
            box-shadow: 0 0 0 3px rgba(232, 98, 42, 0.15);
        }
        .btn-save-profile {
            background-color: #E8622A;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-save-profile:hover {
            background-color: #c94f1d;
        }
        input[readonly] {
            cursor: not-allowed;
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ═══ SIDEBAR ═══ --}}
<div id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-car-front-fill"></i></div>
        <div class="brand-text">
            <h6>SIREMO</h6>
            <small>Sistem Informasi Rental Mobil</small>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="sidebar-section-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-link-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.kendaraan.index') }}"
           class="nav-link-item {{ request()->routeIs('admin.kendaraan*') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> Kendaraan
        </a>
        <a href="{{ route('admin.pelanggan.index') }}"
           class="nav-link-item {{ request()->routeIs('admin.pelanggan*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Pelanggan
        </a>
        <a href="{{ route('admin.transaksi.index') }}"
           class="nav-link-item {{ request()->routeIs('admin.transaksi*') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i> Transaksi
        </a>
        <a href="{{ route('admin.pengembalian.index') }}"
           class="nav-link-item {{ request()->routeIs('admin.pengembalian*') ? 'active' : '' }}">
            <i class="bi bi-arrow-return-left"></i> Pengembalian
        </a>
        <a href="{{ route('admin.ulasan.index') }}"
           class="nav-link-item {{ request()->routeIs('admin.ulasan*') ? 'active' : '' }}">
            <i class="bi bi-star"></i> Ulasan
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'A', 0, 2)) }}</div>
            <div class="user-info">
                <span>{{ auth()->user()->nama_lengkap ?? 'Admin' }}</span>
                <small>{{ auth()->user()->email ?? '' }}</small>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                @csrf
                <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent text-muted" title="Logout">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </button>
            </form>
        </div>
        <button type="button" class="btn-edit-profil" data-bs-toggle="modal" data-bs-target="#editProfilModal">
            Edit Profil
        </button>
    </div>
</div>

{{-- ═══ TOPBAR ═══ --}}
<div id="topbar">
    <div class="topbar-title">
        <h5>@yield('page-title', 'Dashboard')</h5>
        <p>@yield('page-subtitle', 'Selamat datang di SIREMO')</p>
    </div>
    <div class="topbar-right">
        <div class="search-box">
            <i class="bi bi-search text-muted"></i>
            <input type="text" placeholder="Cari ...">
        </div>
        <button class="notif-btn">
            <i class="bi bi-bell"></i>
            <span class="notif-badge">3</span>
        </button>
    </div>
</div>

{{-- ═══ MAIN CONTENT ═══ --}}
<div id="main-content">
    <div class="content-body">
        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Alert Error Global / Validasi Gagal --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Ada kesalahan pada data yang Anda masukkan. Silakan periksa kembali modal edit profil.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

{{-- ═══ GLOBAL MODAL EDIT PROFIL ADMIN ═══ --}}
<div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content custom-profile-modal">
            
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="editProfilModalLabel">Edit Profil Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.profil.update') }}" method="POST">
                @csrf
                @method('PUT') {{-- Menggunakan PUT/PATCH umumnya direkomendasikan untuk update data --}}
                
                <div class="modal-body pt-3">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Nama Lengkap</label>
                        <input type="text" class="form-control bg-light text-muted border-0 custom-input" value="{{ auth()->user()->nama_lengkap }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Email</label>
                        <input type="email" class="form-control bg-light text-muted border-0 custom-input" value="{{ auth()->user()->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" id="lbl_telepon">No. Telepon</label>
                        <input type="tel" name="no_telepon" id="no_telepon" 
                               class="form-control custom-input @error('no_telepon') is-invalid @enderror" 
                               placeholder="+62-8xxx-xxxx-xx" value="{{ old('no_telepon', auth()->user()->no_telepon) }}">
                        @error('no_telepon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" id="lbl_cabang">Cabang Rental</label>
                        <input type="text" name="cabang_rental" id="cabang_rental" 
                               class="form-control custom-input @error('cabang_rental') is-invalid @enderror" 
                               placeholder="Nama Cabang" value="{{ old('cabang_rental', auth()->user()->cabang_rental) }}">
                        @error('cabang_rental')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" id="lbl_alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control custom-input @error('alamat') is-invalid @enderror" 
                                  rows="3" placeholder="Alamat Lengkap admin .....">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary">Status</label>
                        <input type="text" class="form-control bg-light text-muted border-0 custom-input" value="{{ auth()->user()->status ?? 'Aktif' }}" readonly>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-save-profile px-4 py-2 text-white">
                            <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

{{-- Otomatis buka modal jika ada error validasi dari Laravel --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if($errors->any())
            var myModal = new bootstrap.Modal(document.getElementById('editProfilModal'));
            myModal.show();
        @endif
    });
</script>

@stack('scripts')
</body>
</html>