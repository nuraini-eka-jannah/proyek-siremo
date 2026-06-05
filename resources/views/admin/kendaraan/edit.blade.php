@extends('layouts.admin')

@section('title', 'Edit Kendaraan')
@section('page-title', 'Edit Kendaraan')
@section('page-subtitle', 'Manajemen Kendaraan')

@push('styles')
<style>
    .form-card {
        background: #fff;
        border-radius: 16px;
        padding: 28px 32px;
        max-width: 820px;
    }

    .form-section-title {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #E8622A;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #F5EDE8;
    }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #E8DDD8;
        font-size: 13.5px;
        padding: 10px 14px;
        transition: border-color .15s, box-shadow .15s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #E8622A;
        box-shadow: 0 0 0 3px rgba(232,98,42,.12);
    }

    .foto-preview-wrap {
        width: 100%;
        height: 200px;
        border-radius: 12px;
        border: 2px dashed #E0D0CA;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #FAF5F2;
        cursor: pointer;
        transition: border-color .15s;
        position: relative;
    }

    .foto-preview-wrap:hover { border-color: #E8622A; }

    .foto-preview-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 10px;
    }

    .foto-placeholder {
        text-align: center;
        color: #C0A898;
        position: absolute;
    }

    .foto-placeholder i { font-size: 36px; display: block; margin-bottom: 8px; }

    .btn-simpan {
        background: #E8622A;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 11px 28px;
        font-size: 14px;
        font-weight: 600;
        transition: background .15s;
    }

    .btn-simpan:hover { background: #c9521e; color: #fff; }

    .btn-batal {
        background: #F5EDE8;
        color: #555;
        border: none;
        border-radius: 10px;
        padding: 11px 24px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
    }

    .btn-batal:hover { background: #E8DDD8; color: #333; }
</style>
@endpush

@section('content')

<div class="form-card">

    <div class="mb-4">
        <a href="{{ route('admin.kendaraan.index') }}"
           style="color:#E8622A;font-size:13px;text-decoration:none;font-weight:600;">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kendaraan
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger rounded-3 mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li style="font-size:13px;">{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kendaraan.update', $kendaraan->id_mobil) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ── INFORMASI MOBIL ──────────────────────── --}}
        <div class="form-section-title">Informasi Kendaraan</div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Merek <span class="text-danger">*</span></label>
                <input type="text" name="merek" class="form-control @error('merek') is-invalid @enderror"
                       value="{{ old('merek', $kendaraan->merek) }}">
                @error('merek')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Model <span class="text-danger">*</span></label>
                <input type="text" name="model" class="form-control @error('model') is-invalid @enderror"
                       value="{{ old('model', $kendaraan->model) }}">
                @error('model')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Plat Nomor <span class="text-danger">*</span></label>
                <input type="text" name="plat_nomor"
                       class="form-control @error('plat_nomor') is-invalid @enderror"
                       value="{{ old('plat_nomor', $kendaraan->plat_nomor) }}"
                       style="text-transform:uppercase;">
                @error('plat_nomor')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Tahun <span class="text-danger">*</span></label>
                <input type="number" name="tahun"
                       class="form-control @error('tahun') is-invalid @enderror"
                       value="{{ old('tahun', $kendaraan->tahun) }}"
                       min="2000" max="{{ date('Y') + 1 }}">
                @error('tahun')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Warna <span class="text-danger">*</span></label>
                <input type="text" name="warna" class="form-control @error('warna') is-invalid @enderror"
                       value="{{ old('warna', $kendaraan->warna) }}">
                @error('warna')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select @error('kategori') is-invalid @enderror">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach(['keluarga','City Car','Bus/MiniBus','SUV','MPV'] as $kat)
                        <option value="{{ $kat }}"
                            {{ old('kategori', $kendaraan->kategori) == $kat ? 'selected' : '' }}>
                            {{ $kat }}
                        </option>
                    @endforeach
                </select>
                @error('kategori')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Tarif Sewa / Hari (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="tarif_sewa_per_hari"
                       class="form-control @error('tarif_sewa_per_hari') is-invalid @enderror"
                       value="{{ old('tarif_sewa_per_hari', $kendaraan->tarif_sewa_per_hari) }}"
                       min="0">
                @error('tarif_sewa_per_hari')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label class="form-label">Status Ketersediaan <span class="text-danger">*</span></label>
                <select name="status_ketersediaan"
                        class="form-select @error('status_ketersediaan') is-invalid @enderror">
                    @foreach(['Tersedia','Disewa','Perawatan'] as $s)
                        <option value="{{ $s }}"
                            {{ old('status_ketersediaan', $kendaraan->status_ketersediaan) == $s ? 'selected' : '' }}>
                            {{ $s }}
                        </option>
                    @endforeach
                </select>
                @error('status_ketersediaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $kendaraan->deskripsi) }}</textarea>
                @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        {{-- ── FOTO MOBIL ───────────────────────────── --}}
        <div class="form-section-title">Foto Kendaraan</div>
        <div class="mb-4">
            <div class="foto-preview-wrap" onclick="document.getElementById('foto').click()">
                @if($kendaraan->foto)
                    <img id="foto-preview" src="{{ asset('storage/' . $kendaraan->foto) }}" alt="">
                    <div class="foto-placeholder" id="foto-placeholder" style="display:none;">
                        <i class="bi bi-camera-fill"></i>
                        <span>Klik untuk ganti foto</span>
                    </div>
                @else
                    <img id="foto-preview" src="" alt="" style="display:none;">
                    <div class="foto-placeholder" id="foto-placeholder">
                        <i class="bi bi-camera-fill"></i>
                        <span>Klik untuk upload foto<br><small>JPG, PNG maks. 2MB</small></span>
                    </div>
                @endif
            </div>
            <input type="file" name="foto" id="foto" class="d-none"
                    accept="image/jpg,image/jpeg,image/png"
                    onchange="previewFoto(this)">
            <p style="font-size:11px;color:#999;margin-top:6px;">
                Kosongkan jika tidak ingin mengganti foto.
            </p>
            @error('foto')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
        </div>

        {{-- ── ACTIONS ──────────────────────────────── --}}
        <div class="d-flex gap-3 align-items-center">
            <button type="submit" class="btn-simpan">
                <i class="bi bi-floppy-fill me-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.kendaraan.index') }}" class="btn-batal">Batal</a>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('foto-preview').src = e.target.result;
            document.getElementById('foto-preview').style.display = 'block';
            document.getElementById('foto-placeholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush