<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIREMO – Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Menghilangkan ikon mata bawaan Microsoft Edge */
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
        }

        /* Menghilangkan fitur reveal password bawaan Chrome & browser berbasis Chromium lainnya */
        input::-webkit-contacts-auto-fill-button,
        input::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            display: none !important;
            pointer-events: none;
        }
        
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* ── KIRI: Foto / Ilustrasi ────────── */
        .side-left {
            width: 47%;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            background: #1A1A1A;
        }

        .side-left img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: .92;
        }

        /* Fallback jika gambar tidak ada */
        .side-left-fallback {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #E8622A 0%, #FF9A3C 50%, #E8622A 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .fallback-logo {
            font-size: 72px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -2px;
            text-shadow: 0 4px 20px rgba(0,0,0,.2);
        }

        .fallback-sub {
            font-size: 16px;
            color: rgba(255,255,255,.85);
            font-weight: 500;
        }

        /* ── KANAN: Form ───────────────────── */
        .side-right {
            flex: 1;
            background: #FAD9B5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 60px;
            overflow-y: auto;
        }

        /* ── LOGO AREA ─────────────────────── */
        .logo-area {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 36px;
        }

        .logo-icon {
            font-size: 42px;
            color: #C47A20;
        }

        .logo-text {
            font-size: 32px;
            font-weight: 800;
            color: #C47A20;
            letter-spacing: 1px;
        }

        /* ── FORM ──────────────────────────── */
        .form-wrap {
            width: 100%;
            max-width: 460px;
        }

        .field-group {
            margin-bottom: 20px;
        }

        .field-label {
            font-size: 15px;
            font-weight: 700;
            color: #3A2A1A;
            margin-bottom: 8px;
            display: block;
        }

        .field-input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field-icon {
            position: absolute;
            left: 16px;
            font-size: 18px;
            color: #A08060;
            pointer-events: none;
        }

        .field-input {
            width: 100%;
            background: #F5EDE0;
            border: none;
            border-radius: 12px;
            padding: 14px 48px 14px 46px;
            font-size: 14.5px;
            font-family: inherit;
            color: #3A2A1A;
            outline: none;
            transition: background .15s, box-shadow .15s;
        }

        .field-input::placeholder { color: #B89870; }

        .field-input:focus {
            background: #EFE4D2;
            box-shadow: 0 0 0 3px rgba(196,122,32,.2);
        }

        .field-input.is-invalid {
            box-shadow: 0 0 0 3px rgba(220,53,69,.2);
            background: #FFF0EE;
        }

        /* Tombol tindakan di input */
        .pw-actions {
            position: absolute;
            right: 14px;
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .pw-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: #A08060;
            font-size: 18px;
            padding: 2px;
            display: flex;
            align-items: center;
            transition: color .15s;
        }

        .pw-btn:hover { color: #C47A20; }

        /* Error message */
        .field-error {
            font-size: 12px;
            color: #CC2222;
            margin-top: 5px;
            display: none;
        }

        .field-error.show { display: block; }

        /* ── BUTTON LOGIN ──────────────────── */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: #C47A20;
            color: #fff;
            border: none;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            margin-top: 10px;
            transition: background .15s, transform .1s;
            display: block;
        }

        .btn-login:hover  { background: #A8660F; }
        .btn-login:active { transform: scale(.98); }

        /* ── BANTUAN LINKS ─────────────────── */
        .auth-links {
            text-align: left;
            margin-top: 24px;
            font-size: 14px;
            color: #6A4E30;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .auth-links a {
            color: #6A4E30;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-links .register-now {
            color: #3A2A1A;
            font-weight: 700;
        }

        .auth-links .register-now a {
            color: #C47A20;
            font-weight: 700;
        }

        .auth-links a:hover { text-decoration: underline; }

        /* ── ALERT ─────────────────────────── */
        .alert-box {
            background: #FFE0DE;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #A81212;
        }

        .alert-box ul { margin: 0; padding-left: 16px; }

        /* ── RESPONSIVE ────────────────────── */
        @media (max-width: 768px) {
            .side-left { display: none; }
            .side-right { padding: 40px 28px; }
        }
    </style>
</head>
<body>

{{-- ── KIRI: Foto kantor SIREMO ──────────────────────── --}}
<div class="side-left">
    <img src="{{ asset('images/siremo-office.jpg') }}" alt="SIREMO Office"
         onerror="this.style.display='none';document.querySelector('.side-left-fallback').style.display='flex';">
    <div class="side-left-fallback" style="display:none;">
        <div class="fallback-logo">SIREMO</div>
        <div class="fallback-sub">Sistem Informasi Rental Mobil</div>
    </div>
</div>

{{-- ── KANAN: Form Login ─────────────────────────────── --}}
<div class="side-right">

    {{-- Logo --}}
    <div class="logo-area">
        <i class="bi bi-car-front-fill logo-icon"></i>
        <span class="logo-text">LOGIN</span>
    </div>

    {{-- Error Bag --}}
    @if($errors->any())
        <div class="alert-box" style="width:100%;max-width:460px;">
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="form-wrap" method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="field-group">
            <label class="field-label" for="email">Email :</label>
            <div class="field-input-wrap">
                <i class="bi bi-envelope field-icon"></i>
                <input type="email" id="email" name="email"
                       class="field-input @error('email') is-invalid @enderror"
                       placeholder="admin1@gmail.com"
                       value="{{ old('email') }}"
                       autocomplete="email" required autofocus>
            </div>
            @error('email')
                <div class="field-error show">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="field-group">
            <label class="field-label" for="password">Password :</label>
            <div class="field-input-wrap">
                <i class="bi bi-key field-icon"></i>
                <input type="password" id="password" name="password"
                       class="field-input @error('password') is-invalid @enderror"
                       placeholder="password"
                       autocomplete="current-password" required>
                <div class="pw-actions">
                    <button type="button" class="pw-btn" onclick="clearField('password')" title="Hapus">
                        <i class="bi bi-x-circle"></i>
                    </button>
                    <button type="button" class="pw-btn" onclick="togglePw('password', this)" title="Lihat">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>
            @error('password')
                <div class="field-error show">{{ $message }}</div>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-login">Login</button>

        {{-- Link Bantuan --}}
        <div class="auth-links">
            <div class="forgot-link">
                <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
            </div>
            <div class="register-now">
                Belum Punya Akun? <a href="{{ route('register') }}">Sign Up Now</a>
            </div>
        </div>

    </form>
</div>

<script>
/* Toggle show/hide password */
function togglePw(fieldId, btn) {
    const inp = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'bi bi-eye';
    } else {
        inp.type = 'password';
        icon.className = 'bi bi-eye-slash';
    }
}

/* Clear field */
function clearField(fieldId) {
    document.getElementById(fieldId).value = '';
    document.getElementById(fieldId).focus();
}
</script>
</body>
</html>