<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /* ══════════════════════════════════════════
     |  LOGIN
     ══════════════════════════════════════════ */

    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Saringan pengalihan berdasarkan Role saat LOGIN
            if ($user->role === 'super_admin') {
                return redirect()->route('superadmin.dashboard'); // Jika super admin
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard'); // Jika admin biasa
            }

            return redirect()->route('penyewa.home'); // Jika penyewa tembus ke web
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

        //API Login
        public function apiLogin(Request $request)
        {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            // Verifikasi user dan password
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Email atau password salah.'], 401);
            }

            // Sekarang $user sudah pasti ada dan valid
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login berhasil',
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 200);
        }

    /* ══════════════════════════════════════════
     |  REGISTER
     ══════════════════════════════════════════ */

    public function showRegister()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'username'     => 'required|string|max:50|unique:users,username',
            'nama_lengkap' => 'nullable',
            'email'        => 'required|email|max:50|unique:users,email',
            'password'     => 'required|min:8|confirmed',
            'role'         => 'nullable|in:superadmin,admin,penyewa'
        ], [
            'username.unique'     => 'Username sudah digunakan, cari yang lain.',
            'email.unique'        => 'Email sudah terdaftar.',
            'password.min'        => 'Password minimal 8 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
        ]);

        // Menyimpan data ke kolom database yang sesuai
        $user = User::create([
            'username'     => $request->username,
            'name'         => $request->nama_lengkap,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'admin', // default role
        ]);

        Auth::login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Akun Admin berhasil dibuat! Selamat datang, ' . $user->nama_lengkap . '.');
    }

    //API Register
    public function apiRegister(Request $request)
{
    // 1. Gunakan Validator secara manual agar bisa mengembalikan JSON jika gagal
    $validator = Validator::make($request->all(), [
        'name'       => 'required|string|max:255',
        'email'      => 'required|string|email|max:255|unique:users',
        'password'   => 'required|string|min:6',
        'no_telepon' => 'required|string',
        'username'   => 'required|string|unique:users',
    ]);

    // 2. Jika validasi gagal, kembalikan JSON dengan status 422 (Unprocessable Entity)
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validasi gagal',
            'errors'  => $validator->errors()
        ], 422);
    }

    // 3. Simpan user
    $user = User::create([
        'name'       => $request->name,
        'email'      => $request->email,
        'password'   => Hash::make($request->password),
        'no_telepon' => $request->no_telepon,
        'username'   => $request->username,
        'role'       => 'penyewa', 
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message'      => 'Registrasi berhasil',
        'access_token' => $token,
        'token_type'   => 'Bearer',
    ], 201);
}

    /* ══════════════════════════════════════════
     |  LUPA PASSWORD
     ══════════════════════════════════════════ */

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }

    /* ══════════════════════════════════════════
     |  RESET PASSWORD
     ══════════════════════════════════════════ */

    public function showResetPassword(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function processResetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Password berhasil direset! Silakan login dengan password baru.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}