<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIREMO – Lupa Password</title>
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

        /* ── KIRI ──────────────────────────── */
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

        .fallback-logo { font-size: 72px; font-weight: 800; color: #fff; letter-spacing: -2px; }
        .fallback-sub  { font-size: 16px; color: rgba(255,255,255,.85); font-weight: 500; }

        /* ── KANAN ─────────────────────────── */
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

        /* ── LOGO ──────────────────────────── */
        .logo-area {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .logo-icon { font-size: 42px; color: #C47A20; }
        .logo-text { font-size: 32px; font-weight: 800; color: #C47A20; }

        .logo-subtitle {
            font-size: 14px;
            color: #8A6040;
            text-align: center;
            margin-bottom: 36px;
            max-width: 380px;
            line-height: 1.6;
        }

        /* ── FORM ──────────────────────────── */
        .form-wrap { width: 100%; max-width: 460px; }

        .field-group  { margin-bottom: 20px; }

        .field-label {
            font-size: 15px;
            font-weight: 700;
            color: #3A2A1A;
            margin-bottom: 8px;
            display: block;
        }

        .field-input-wrap { position: relative; display: flex; align-items: center; }

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
            padding: 14px 16px 14px 46px;
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

        .field-input.is-invalid { box-shadow: 0 0 0 3px rgba(220,53,69,.2); background: #FFF0EE; }

        .field-error { font-size: 12px; color: #CC2222; margin-top: 5px; }

        /* ── BUTTON ────────────────────────── */
        .btn-kirim {
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
        }

        .btn-kirim:hover  { background: #A8660F; }
        .btn-kirim:active { transform: scale(.98); }

        /* ── ALERT SUCCESS ─────────────────── */
        .alert-success-box {
            background: #D4F5E2;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert-success-box i { font-size: 20px; color: #1A7A48; flex-shrink: 0; margin-top: 2px; }
        .alert-success-box p { font-size: 13.5px; color: #1A5A35; margin: 0; line-height: 1.6; }

        /* ── ALERT ERROR ───────────────────── */
        .alert-error-box {
            background: #FFE0DE;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #A81212;
        }

        /* ── LINK KEMBALI ──────────────────── */
        .back-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13.5px;
        }

        .back-link a {
            color: #C47A20;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover { text-decoration: underline; }

        /* ── STEP INDICATOR ────────────────── */
        .steps {
            display: flex;
            gap: 8px;
            margin-bottom: 28px;
            justify-content: center;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #B89870;
        }

        .step-num {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: #E8D0B0;
            color: #8A6040;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }

        .step.active .step-num { background: #C47A20; color: #fff; }
        .step.active { color: #C47A20; }
        .step-divider { width: 30px; height: 2px; background: #E8D0B0; align-self: center; }

        @media (max-width: 768px) {
            .side-left { display: none; }
            .side-right { padding: 40px 28px; }
        }
    </style>
</head>
<body>

{{-- ── KIRI ───────────────────────────────────────────── --}}
<div class="side-left">
    <img src="{{ asset('images/siremo-office.jpg') }}" alt="SIREMO"
         onerror="this.style.display='none';document.querySelector('.side-left-fallback').style.display='flex';">
    <div class="side-left-fallback" style="display:none;">
        <div class="fallback-logo">SIREMO</div>
        <div class="fallback-sub">Sistem Informasi Rental Mobil</div>
    </div>
</div>

{{-- ── KANAN ───────────────────────────────────────────── --}}
<div class="side-right">

    {{-- Logo --}}
    <div class="logo-area">
        <i class="bi bi-car-front-fill logo-icon"></i>
        <span class="logo-text">SIREMO</span>
    </div>
    <p class="logo-subtitle">
        Masukkan email yang terdaftar. Kami akan mengirimkan<br>
        link untuk mereset password Anda.
    </p>

    {{-- Step Indicator --}}
    <div class="steps">
        <div class="step active">
            <div class="step-num">1</div>
            <span>Verifikasi Email</span>
        </div>
        <div class="step-divider"></div>
        <div class="step">
            <div class="step-num">2</div>
            <span>Reset Password</span>
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('status'))
        <div class="alert-success-box" style="width:100%;max-width:460px;">
            <i class="bi bi-check-circle-fill"></i>
            <p>
                Link reset password telah dikirim ke email Anda.<br>
                Silakan cek inbox atau folder <strong>Spam</strong>.
            </p>
        </div>
    @endif

    {{-- Alert Error --}}
    @if($errors->any())
        <div class="alert-error-box" style="width:100%;max-width:460px;">
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    {{-- Form --}}
    <form class="form-wrap" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="field-group">
            <label class="field-label" for="email">Email terdaftar :</label>
            <div class="field-input-wrap">
                <i class="bi bi-envelope field-icon"></i>
                <input type="email" id="email" name="email"
                       class="field-input @error('email') is-invalid @enderror"
                       placeholder="contoh@email.com"
                       value="{{ old('email') }}"
                       autocomplete="email" required>
            </div>
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn-kirim">
            <i class="bi bi-send-fill me-2"></i> Kirim Link Reset
        </button>

        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </form>

</div>
</body>
</html>