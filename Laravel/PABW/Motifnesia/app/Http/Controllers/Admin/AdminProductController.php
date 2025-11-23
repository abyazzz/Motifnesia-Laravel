<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

class AdminProductController extends Controller
{
    public function index()
    {
        // Ambil produk dari database
        $produkList = Produk::orderBy('id', 'desc')->get();

        return view('admin.pages.daftarProduk', [
            'produkList' => $produkList,
            'activePage' => 'daftar-produk'
        ]);
    }
}
