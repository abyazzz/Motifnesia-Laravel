<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Models\Produk;
use App\Models\KontenSlideShow;

class CustomerProductController extends Controller
{
    /**
     * Halaman home dengan slideshow + produk (dengan filter)
     */
    public function index()
    {
        // Load slideshow slides
        $slides = KontenSlideShow::orderBy('urutan')->get();

        // Query produk dengan filter
        $query = Produk::query();

        // Filter Search (nama produk, material, kategori, tags, deskripsi)
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'LIKE', "%{$search}%");
            });
        }

        // Filter Gender
        if (request('gender')) {
            $query->where('gender', request('gender'));
        }

        // Filter Jenis Lengan
        if (request('jenis_lengan')) {
            $query->where('jenis_lengan', request('jenis_lengan'));
        }

        // Filter Harga Range
        if (request('price_range')) {
            $range = explode('-', request('price_range'));
            if (count($range) == 2) {
                $query->whereBetween('harga', [(int)$range[0], (int)$range[1]]);
            }
        }

        // Load products dengan rating dari reviews
        $productService = app(ProductService::class);
        $products = $query->orderBy('id', 'desc')->get()->map(function($p) use ($productService) {
            return $productService->formatProductWithRating($p);
        });

        return view('customer.pages.homePage', compact('products', 'slides'));
    }

    /**
     * Detail produk dengan reviews dan related products
     */
    public function show($id)
    {
        $product = Produk::with('reviews.user')->findOrFail($id);

        // Normalize product data
        $productData = [
            'id'        => $product->id,
            'nama'      => $product->nama_produk,
            'harga'     => $product->harga,
            'harga_diskon' => $product->harga_diskon ?? $product->harga,
            'diskon_persen' => $product->diskon_persen ?? 0,
            'gambar'    => $product->gambar,
            'deskripsi' => $product->deskripsi,
            'material'  => $product->material,
            'proses'    => $product->proses,
            'kategori'  => $product->kategori,
            'stok'      => $product->stok,
        ]; 

        // Get reviews untuk produk ini
        $reviews = $product->reviews()->with('user')->latest()->get();

        // Get related products (produk lain, exclude current)
        $productService = app(ProductService::class);
        $relatedProducts = Produk::where('id', '!=', $id)
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get()
            ->map(function($p) use ($productService) {
                return $productService->formatProductWithRating($p);
            });

        return view('customer.pages.detailProduct', [
            'product' => $productData,
            'reviews' => $reviews,
            'relatedProducts' => $relatedProducts
        ]);
    }
}
