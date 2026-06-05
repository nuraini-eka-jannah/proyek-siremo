<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIREMO – Reset Password</title>
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

        .side-left {
            width: 47%;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            background: #1A1A1A;
        }

        .side-left img { width: 100%; height: 100%; object-fit: cover; opacity: .92; }

        .side-left-fallback {
            width: 100%; height: 100%;
            background: linear-gradient(135deg, #E8622A 0%, #FF9A3C 50%, #E8622A 100%);
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 20px;
        }

        .fallback-logo { font-size: 72px; font-weight: 800; color: #fff; letter-spacing: -2px; }
        .fallback-sub  { font-size: 16px; color: rgba(255,255,255,.85); font-weight: 500; }

        .side-right {
            flex: 1;
            background: #FAD9B5;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 40px 60px;
            overflow-y: auto;
        }

        .logo-area { display: flex; align-items: center; gap: 14px; margin-bottom: 10px; }
        .logo-icon  { font-size: 42px; color: #C47A20; }
        .logo-text  { font-size: 32px; font-weight: 800; color: #C47A20; }

        .logo-subtitle {
            font-size: 14px; color: #8A6040;
            text-align: center; margin-bottom: 32px;
            max-width: 380px; line-height: 1.6;
        }

        /* Step indicator */
        .steps { display: flex; gap: 8px; margin-bottom: 28px; justify-content: center; }
        .step { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #B89870; }
        .step-num {
            width: 24px; height: 24px; border-radius: 50%;
            background: #E8D0B0; color: #8A6040;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700;
        }
        .step.active .step-num { background: #C47A20; color: #fff; }
        .step.active { color: #C47A20; }
        .step.done .step-num  { background: #2DCE89; color: #fff; }
        .step-divider { width: 30px; height: 2px; background: #C47A20; align-self: center; }

        .form-wrap { width: 100%; max-width: 460px; }
        .field-group { margin-bottom: 20px; }

        .field-label {
            font-size: 15px; font-weight: 700; color: #3A2A1A;
            margin-bottom: 8px; display: block;
        }

        .field-input-wrap { position: relative; display: flex; align-items: center; }

        .field-icon {
            position: absolute; left: 16px;
            font-size: 18px; color: #A08060; pointer-events: none;
        }

        .field-input {
            width: 100%; background: #F5EDE0; border: none;
            border-radius: 12px; padding: 14px 48px 14px 46px;
            font-size: 14.5px; font-family: inherit; color: #3A2A1A;
            outline: none; transition: background .15s, box-shadow .15s;
        }

        .field-input::placeholder { color: #B89870; }
        .field-input:focus { background: #EFE4D2; box-shadow: 0 0 0 3px rgba(196,122,32,.2); }
        .field-input.is-invalid { box-shadow: 0 0 0 3px rgba(220,53,69,.2); background: #FFF0EE; }

        .pw-actions { position: absolute; right: 14px; display: flex; gap: 6px; align-items: center; }
        .pw-btn { background: none; border: none; cursor: pointer; color: #A08060; font-size: 18px; padding: 2px; display: flex; align-items: center; transition: color .15s; }
        .pw-btn:hover { color: #C47A20; }

        .field-error { font-size: 12px; color: #CC2222; margin-top: 5px; }

        /* Password strength */
        .pw-strength-bar {
            height: 4px; border-radius: 4px;
            background: #E8D0B0; margin-top: 8px;
            overflow: hidden;
        }

        .pw-strength-fill {
            height: 100%; width: 0;
            border-radius: 4px;
            transition: width .3s, background .3s;
        }

        .pw-strength-label {
            font-size: 11px; color: #A08060; margin-top: 4px;
        }

        .btn-reset {
            width: 100%; padding: 16px;
            background: #C47A20; color: #fff;
            border: none; border-radius: 14px;
            font-size: 17px; font-weight: 700;
            font-family: inherit; cursor: pointer;
            margin-top: 10px;
            transition: background .15s, transform .1s;
        }

        .btn-reset:hover  { background: #A8660F; }
        .btn-reset:active { transform: scale(.98); }

        .back-link {
            text-align: center; margin-top: 20px; font-size: 13.5px;
        }

        .back-link a {
            color: #C47A20; font-weight: 700; text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
        }

        .back-link a:hover { text-decoration: underline; }

        .alert-error-box {
            background: #FFE0DE; border-radius: 10px;
            padding: 12px 16px; margin-bottom: 20px;
            font-size: 13px; color: #A81212;
        }

        @media (max-width: 768px) {
            .side-left { display: none; }
            .side-right { padding: 40px 28px; }
        }
    </style>
</head>
<body>

<div class="side-left">
    <img src="{{ asset('images/siremo-office.jpg') }}" alt="SIREMO"
         onerror="this.style.display='none';document.querySelector('.side-left-fallback').style.display='flex';">
    <div class="side-left-fallback" style="display:none;">
        <div class="fallback-logo">SIREMO</div>
        <div class="fallback-sub">Sistem Informasi Rental Mobil</div>
    </div>
</div>

<div class="side-right">

    <div class="logo-area">
        <i class="bi bi-car-front-fill logo-icon"></i>
        <span class="logo-text">SIREMO</span>
    </div>
    <p class="logo-subtitle">Buat password baru untuk akun Anda.</p>

    {{-- Step Indicator --}}
    <div class="steps">
        <div class="step done">
            <div class="step-num"><i class="bi bi-check" style="font-size:13px;"></i></div>
            <span>Verifikasi Email</span>
        </div>
        <div class="step-divider"></div>
        <div class="step active">
            <div class="step-num">2</div>
            <span>Reset Password</span>
        </div>
    </div>

    @if($errors->any())
        <div class="alert-error-box" style="width:100%;max-width:460px;">
            @foreach($errors->all() as $err)
                <div>{{ $err }}</div>
            @endforeach
        </div>
    @endif

    <form class="form-wrap" method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email (hidden tapi tampil readonly) --}}
        <div class="field-group">
            <label class="field-label">Email :</label>
            <div class="field-input-wrap">
                <i class="bi bi-envelope field-icon"></i>
                <input type="email" name="email"
                       class="field-input @error('email') is-invalid @enderror"
                       value="{{ old('email', $email ?? '') }}"
                       placeholder="email terdaftar" required>
            </div>
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password Baru --}}
        <div class="field-group">
            <label class="field-label" for="password">Password Baru :</label>
            <div class="field-input-wrap">
                <i class="bi bi-key field-icon"></i>
                <input type="password" id="password" name="password"
                       class="field-input @error('password') is-invalid @enderror"
                       placeholder="password baru"
                       oninput="cekKekuatan(this.value)"
                       autocomplete="new-password" required>
                <div class="pw-actions">
                    <button type="button" class="pw-btn" onclick="clearField('password')">
                        <i class="bi bi-x-circle"></i>
                    </button>
                    <button type="button" class="pw-btn" onclick="togglePw('password', this)">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>
            {{-- Strength bar --}}
            <div class="pw-strength-bar">
                <div class="pw-strength-fill" id="strength-fill"></div>
            </div>
            <div class="pw-strength-label" id="strength-label">Minimal 8 karakter</div>
            @error('password')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="field-group">
            <label class="field-label" for="password_confirmation">Konfirmasi Password :</label>
            <div class="field-input-wrap">
                <i class="bi bi-key field-icon"></i>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="field-input"
                       placeholder="ulangi password baru"
                       autocomplete="new-password" required>
                <div class="pw-actions">
                    <button type="button" class="pw-btn" onclick="clearField('password_confirmation')">
                        <i class="bi bi-x-circle"></i>
                    </button>
                    <button type="button" class="pw-btn" onclick="togglePw('password_confirmation', this)">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-reset">
            <i class="bi bi-lock-fill me-2"></i> Reset Password
        </button>

        <div class="back-link">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </form>

</div>

<script>
function togglePw(fieldId, btn) {
    const inp  = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.className = 'bi bi-eye';
    } else {
        inp.type = 'password';
        icon.className = 'bi bi-eye-slash';
    }
}

function clearField(fieldId) {
    document.getElementById(fieldId).value = '';
    document.getElementById(fieldId).focus();
}

function cekKekuatan(pw) {
    const fill  = document.getElementById('strength-fill');
    const label = document.getElementById('strength-label');
    let score = 0;
    if (pw.length >= 8)                      score++;
    if (/[A-Z]/.test(pw))                   score++;
    if (/[0-9]/.test(pw))                   score++;
    if (/[^A-Za-z0-9]/.test(pw))            score++;

    const map = [
        { w: '0%',   bg: '#E8D0B0', text: 'Minimal 8 karakter' },
        { w: '25%',  bg: '#E85050', text: 'Lemah' },
        { w: '50%',  bg: '#F0A020', text: 'Sedang' },
        { w: '75%',  bg: '#30B060', text: 'Kuat' },
        { w: '100%', bg: '#1A7A48', text: 'Sangat Kuat' },
    ];

    fill.style.width      = map[score].w;
    fill.style.background = map[score].bg;
    label.textContent     = map[score].text;
    label.style.color     = map[score].bg;
}
</script>
</body>
</html>