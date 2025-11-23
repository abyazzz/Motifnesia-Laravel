<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;

class ProductManagementController extends Controller
{
    // Data dummy Produk
    private $products = [
        ['id' => 1, 'name' => 'Batik Kemeja A', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 2, 'name' => 'Batik Kemeja B', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 3, 'name' => 'Batik Kemeja C', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 4, 'name' => 'Batik Kemeja D', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 5, 'name' => 'Batik Kemeja E', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 6, 'name' => 'Batik Kemeja F', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 7, 'name' => 'Batik Kemeja G', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 8, 'name' => 'Batik Kemeja H', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 9, 'name' => 'Batik Kemeja I', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 10, 'name' => 'Batik Kemeja J', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 11, 'name' => 'Batik Kemeja K', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 12, 'name' => 'Batik Kemeja L', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 13, 'name' => 'Batik Kemeja M', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 14, 'name' => 'Batik Kemeja N', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
        ['id' => 15, 'name' => 'Batik Kemeja O', 'price' => 99, 'stock' => 99, 'image' => '/images/dummy_batik.png'],
    ];

    /**
     * Menampilkan halaman kelola produk (grid view).
     */
    public function index()
    {
        // Ambil semua produk dari DB
        $products = Produk::orderBy('id', 'desc')->get();

        return view('admin.pages.productManagement', [
            'products' => $products,
            'activePage' => 'product-management'
        ]);
    }
}