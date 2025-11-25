<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\KontenSlideShow;

class ProductController extends Controller 
{
    public function index()
    {
        // load products from database (fallback to empty collection)
        $products = Produk::orderBy('id', 'desc')->get()->map(function($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama_produk ?? $p->nama ?? '',
                'harga' => $p->harga ?? 0,
                'gambar' => $p->gambar ?? '',
                'deskripsi' => $p->deskripsi ?? '',
            ];
        });

        // load slideshow rows ordered by `urutan`
        $slides = KontenSlideShow::orderBy('urutan')->get();

        return view('homePage', compact('products', 'slides'));
    }

    public function show($id)
    {
        $products = [
            [
                'id' => 1,
                'nama' => 'Batik Mega Mendung',
                'harga' => 150000,
                'gambar' => 'batik1.jpg',
                'deskripsi' => 'Motif klasik dengan nuansa biru khas Cirebon.'
            ],
            [
                'id' => 2,
                'nama' => 'Batik Parang Rusak',
                'harga' => 175000,
                'gambar' => 'batik2.jpg',
                'deskripsi' => 'Motif khas keraton Yogyakarta yang melambangkan kekuatan.'
            ],
            [
                'id' => 3,
                'nama' => 'Batik Kawung',
                'harga' => 160000,
                'gambar' => 'batik3.jpg',
                'deskripsi' => 'Motif geometris elegan yang sering digunakan dalam acara formal.'
            ],
        ];

        // cari produk berdasarkan id. Pastikan $id di-cast ke int
        // Meskipun di route secara default string, tapi biar lebih aman saat perbandingan.
        $product = collect($products)->firstWhere('id', (int)$id);

        if (!$product) {
            abort(404);
        }

        return view('detailProduct', compact('product'));
    }
}