<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckOutController extends Controller
{
    /**
     * Tampilkan halaman konfirmasi transaksi/checkout.
     */
    public function index()
    {
        // Data Dummy untuk ditampilkan di halaman transaksi
        $transactionData = [
            'item' => [
                'nama' => 'Batik Mega Mendung',
                'ukuran' => 'M',
                'jumlah' => 1,
                'harga' => 701522, // Harga per item
                'gambar' => 'placeholder_batik.jpg', // Placeholder gambar
            ],
            'alamat' => 'Jl. Kebon Jeruk No. 12, Jakarta', // Alamat dummy
            'subtotal' => 701522,
            'ongkir_reguler' => 15000,
            'ongkir_ekspres' => 20000,
            'ongkir_ekonomis' => 0,
        ];

        // Karena ini hanya tampilan, kita hitung totalnya secara dummy
        $totalHarga = $transactionData['subtotal'];
        $ongkosKirim = 0; // Asumsi defaultnya Rp0 atau belum dipilih
        $totalBayar = $totalHarga + $ongkosKirim;


        return view('checkOut', compact('transactionData', 'totalHarga', 'ongkosKirim', 'totalBayar'));
    }
}