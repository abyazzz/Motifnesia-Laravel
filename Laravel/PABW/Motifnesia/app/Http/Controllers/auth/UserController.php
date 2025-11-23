<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

        // Daftar Akun Dummy (Tambahkan field 'role' untuk identifikasi)
        $accounts = [
            // AKUN 1: ADMIN (Role tertinggi, redirect ke Dashboard Admin)
            [
                'username' => 'admin',
                'password' => '123456',
                'nama' => 'Admin Motifnesia',
                'role' => 'admin'
            ],
            // AKUN 2: USER REGULER (Role User, redirect ke Home Page)
            [
                'username' => 'user', // Akun user biasa
                'password' => '456789', // Password user biasa
                'nama' => 'Pelanggan Setia',
                'role' => 'user'
            ]
        ];
        
        // Cari akun yang cocok
        $authenticatedAccount = null;
        foreach ($accounts as $akun) {
            if ($username === $akun['username'] && $password === $akun['password']) {
                $authenticatedAccount = $akun;
                break;
            }
        }

        if ($authenticatedAccount) {
            // Set data akun ke session
            session(['user' => $authenticatedAccount]);

            // Cek Role dan Redirect
            if ($authenticatedAccount['role'] === 'admin') {
                // Arahkan ke rute Admin (Contoh: Daftar Produk)
                return redirect()->route('admin.daftar-produk')->with('success', 'Berhasil login sebagai Admin!');
            } else {
                // Arahkan ke rute Home Page
                return redirect()->route('home')->with('success', 'Berhasil login!');
            }
        } else {
            return back()->with('error', 'Username atau password salah!');
        }
    }

    // Halaman register
    public function register()
    {
        return view('auth.register');
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
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }
}
