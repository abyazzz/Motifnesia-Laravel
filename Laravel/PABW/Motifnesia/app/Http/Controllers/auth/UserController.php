<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Halaman login
    public function login()
    {
        return view('auth.login');
    }

    // Proses login dengan multiple role (Admin & User Reguler)
    public function doLogin(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // Coba cari user di database berdasarkan email atau name
        $user = User::where('email', $username)->orWhere('name', $username)->first();

        if ($user && Hash::check($password, $user->password)) {
            // Simpan session lengkap (tambahkan id user)
            $sessionUser = [
                'id' => $user->id,
                'username' => $user->name,
                'nama' => $user->full_name ?? $user->name,
                'email' => $user->email,
                'profile_pic' => $user->profile_pic ?? null,
                'role' => ($user->role ?? 'customer'),
            ];
            session(['user' => $sessionUser]);
            if ($sessionUser['role'] === 'admin') {
                return redirect()->route('admin.product.management.index')->with('success', 'Berhasil login sebagai Admin!');
            } else {
                return redirect()->route('customer.home')->with('success', 'Berhasil login!');
            }
        }

        // Jika tidak ditemukan di DB, fallback ke akun dummy yang disimpan di session (atau default)
        $accounts = session('accounts', [
            [
                'username' => 'admin',
                'password' => '123456',
                'nama' => 'Admin Motifnesia',
                'role' => 'admin'
            ],
            [
                'username' => 'user',
                'password' => '456789',
                'nama' => 'Pelanggan Setia',
                'role' => 'user'
            ]
        ]);

        // Cari akun yang cocok di fallback
        $authenticatedAccount = null;
        foreach ($accounts as $akun) {
            if ($username === $akun['username'] && $password === $akun['password']) {
                $authenticatedAccount = $akun;
                break;
            }
        }

        if ($authenticatedAccount) {
            session(['user' => $authenticatedAccount]);
            if ($authenticatedAccount['role'] === 'admin') {
                return redirect()->route('admin.product.management.index')->with('success', 'Berhasil login sebagai Admin!');
            } else {
                return redirect()->route('customer.home')->with('success', 'Berhasil login!');
            }
        }

        return back()->with('error', 'Username atau password salah!');
    }

    // Halaman register (GET)
    public function register()
    {
        return view('auth.register');
    }

    // Proses register (POST)
    public function doRegister(Request $request)
    {
        // Validasi form sesuai fields di view
        $request->validate([
            'username' => 'required|min:3',
            'full_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        // Buat user baru di database, role default 'customer'.
        $user = User::create([
            'name' => $request->username,
            'full_name' => $request->full_name ?? null,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        if ($user) {
            return redirect()->route('auth.login')->with('success', 'Registrasi berhasil! Silakan login.');
        }

        return back()->with('error', 'Gagal mendaftar, silakan coba lagi.')->withInput();
    }

    // Halaman forgot password
    public function forgot()
    {
        return view('auth.forgot');
    }

    // Logout
    public function logout()
    {
        session()->forget('user');
        return redirect()->route('auth.login')->with('success', 'Berhasil logout.');
    }
}
