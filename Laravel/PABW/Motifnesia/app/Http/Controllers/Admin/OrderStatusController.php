<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    // Data dummy Status Pembelian
    private $orders = [
        ['id' => 1, 'customer_name' => 'Abay', 'product_detail' => 'Batik Batik - M', 'address' => 'Jl. Telekomunikasi', 'status' => 'Diproses'],
        // Duplikasi untuk mengisi daftar panjang dan memberikan variasi status
        ['id' => 2, 'customer_name' => 'Abay', 'product_detail' => 'Batik Batik - M', 'address' => 'Jl. Telekomunikasi', 'status' => 'Dikemas'],
        ['id' => 3, 'customer_name' => 'Sabdila', 'product_detail' => 'Batik Batik - M', 'address' => 'Jl. Telekomunikasi', 'status' => 'Diproses'],
        ['id' => 4, 'customer_name' => 'Rosyadi', 'product_detail' => 'Batik Batik - M', 'address' => 'Jl. Telekomunikasi', 'status' => 'Dalam perjalanan'],
        ['id' => 5, 'customer_name' => 'Fadhil', 'product_detail' => 'Batik Batik - M', 'address' => 'Jl. Telekomunikasi', 'status' => 'Diproses'],
        ['id' => 6, 'customer_name' => 'Zaky', 'product_detail' => 'Batik Batik - M', 'address' => 'Jl. Telekomunikasi', 'status' => 'Sampai'],
        ['id' => 7, 'customer_name' => 'Salman', 'product_detail' => 'Batik Batik - M', 'address' => 'Jl. Telekomunikasi', 'status' => 'Diproses'],
    ];

    /**
     * Menampilkan halaman status pembelian.
     */
    public function index()
    {
        // Variabel statusOptions DIHAPUS dari sini

        return view('admin.pages.orderStatus', [
            'orders' => $this->orders,
            'activePage' => 'order-status'
            // 'statusOptions' sudah tidak dikirim
        ]);
    }
}