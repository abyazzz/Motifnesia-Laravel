<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShoppingCartController extends Controller
{
    /**
     * Tampilkan halaman keranjang belanja (Shopping Cart).
     * Saat ini hanya me-load view dummy.
     */
    public function index()
    {
        // Data dummy produk di keranjang (misalnya dari session/DB nanti)
        $cartItems = [
            [
                'id' => 1,
                'nama' => 'Batik Mega Mendung',
                'ukuran' => 'M',
                'harga' => 701522,
                'gambar' => 'placeholder_batik.jpg',
                'jumlah' => 1,
            ],
            // Kamu bisa tambahkan item lain di sini
        ];

        // Contoh perhitungan total (dummy)
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['harga'] * $item['jumlah'];
        });

        $data = [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal, // Belum ada ongkir/diskon
        ];

        // Mengarahkan ke view shoppingCart.blade.php
        return view('customer.pages.shoppingCart', $data);
    }
}