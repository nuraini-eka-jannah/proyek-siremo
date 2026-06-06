<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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
            'nama_lengkap' => 'required|string|max:100',
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