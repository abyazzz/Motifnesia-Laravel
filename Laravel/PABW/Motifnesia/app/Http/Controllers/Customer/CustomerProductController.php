<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\Produk;
use App\Models\KontenSlideShow;

class CustomerProductController extends Controller
{
    /**
     * Halaman home dengan slideshow + produk
     */
    public function index()
    {
        // Load slideshow slides
        $slides = KontenSlideShow::orderBy('urutan')->get();

        // Load products dan normalize ke array format untuk product-card component
        $products = Produk::orderBy('id', 'desc')->get()->map(function($p) {
            return [
                'id'        => $p->id,
                'nama'      => $p->nama_produk ?? '',
                'harga'     => $p->harga ?? 0,
                'gambar'    => $p->gambar ?? '',
                'deskripsi' => $p->deskripsi ?? '',
            ];
        });

        return view('customer.pages.homePage', compact('products', 'slides'));
    }

    /**
     * Detail produk
     */
    public function show($id)
    {
        $product = Produk::findOrFail($id);

        // Normalize ke array format untuk backward compatibility dengan view
        $productData = [
            'id'        => $product->id,
            'nama'      => $product->nama_produk,
            'harga'     => $product->harga,
            'gambar'    => $product->gambar,
            'deskripsi' => $product->deskripsi,
            'material'  => $product->material,
            'proses'    => $product->proses,
            'kategori'  => $product->kategori,
            'stok'      => $product->stok,
        ];

        return view('customer.pages.detailProduct', ['product' => $productData]);
    }
}
