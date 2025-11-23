<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    // Data dummy Permintaan Retur
    private $returns = [
        ['id' => 1, 'customer_id' => 1001, 'customer_name' => 'Abay', 'product_name' => 'Batik Cap Halus', 'reason' => 'Ukuran tidak sesuai', 'status' => 'Pending'],
        ['id' => 2, 'customer_id' => 1002, 'customer_name' => 'Sabdila', 'product_name' => 'Batik Cap Halus', 'reason' => 'Ukuran tidak sesuai', 'status' => 'Pending'],
        ['id' => 3, 'customer_id' => 1003, 'customer_name' => 'Rosyadi', 'product_name' => 'Batik Cap Halus', 'reason' => 'Ukuran tidak sesuai', 'status' => 'Pending'],
        ['id' => 4, 'customer_id' => 1004, 'customer_name' => 'Fadhil', 'product_name' => 'Batik Cap Halus', 'reason' => 'Ukuran tidak sesuai', 'status' => 'Pending'],
        ['id' => 5, 'customer_id' => 1005, 'customer_name' => 'Zaky', 'product_name' => 'Batik Cap Halus', 'reason' => 'Ukuran tidak sesuai', 'status' => 'Pending'],
        ['id' => 6, 'customer_id' => 1006, 'customer_name' => 'Salman', 'product_name' => 'Batik Cap Halus', 'reason' => 'Ukuran tidak sesuai', 'status' => 'Pending'],
        // Duplikasi data untuk mengisi tabel
        ['id' => 7, 'customer_id' => 1007, 'customer_name' => 'Andi', 'product_name' => 'Batik Cap Halus', 'reason' => 'Barang cacat', 'status' => 'Pending'],
        ['id' => 8, 'customer_id' => 1008, 'customer_name' => 'Bela', 'product_name' => 'Batik Cap Halus', 'reason' => 'Salah kirim', 'status' => 'Pending'],
    ];

    /**
     * Menampilkan halaman kelola retur.
     */
    public function index()
    {
        return view('admin.pages.returnManagement', [
            'returns' => $this->returns,
            'activePage' => 'returns'
        ]);
    }
}