<?php

namespace App\Http\Controllers\Customer;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PurchaseHistoryController extends Controller
{
    /**
     * Data dummy untuk Riwayat Pembelian.
     */
    public static function getHistoryData()
    {
        // Data Dummy Pembelian (diselang-seling Beri Ulasan/Lihat Ulasan)
        return [
            [
                'id' => 101, 'nama' => 'Batik Kawung', 
                'gambar' => 'batik_kawung.jpg', 'status_ulasan' => 'beri',
            ],
            [
                'id' => 102, 'nama' => 'Batik Parang', 
                'gambar' => 'batik_parang.jpg', 'status_ulasan' => 'beri',
            ],
            [
                'id' => 103, 'nama' => 'Batik Lereng', 
                'gambar' => 'batik_lereng.jpg', 'status_ulasan' => 'beri',
            ],
            [
                'id' => 104, 'nama' => 'Batik Pisan Bali', 
                'gambar' => 'batik_pisan_bali.jpg', 'status_ulasan' => 'lihat', // Sudah diulas
            ],
            [
                'id' => 105, 'nama' => 'Batik Lasem', 
                'gambar' => 'batik_lasem.jpg', 'status_ulasan' => 'beri',
            ],
            [
                'id' => 106, 'nama' => 'Batik Mega Mendung', 
                'gambar' => 'batik_mega_mendung.jpg', 'status_ulasan' => 'lihat',
            ],
            [
                'id' => 107, 'nama' => 'Batik Sidomukti', 
                'gambar' => 'batik_sidomukti.jpg', 'status_ulasan' => 'beri',
            ],
        ];
    }
}