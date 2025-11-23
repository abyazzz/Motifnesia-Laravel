<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Data dummy Penjualan Bulanan (untuk Grafik)
    private $monthlySales = [
        ['month' => 'Januari', 'value' => 70, 'color' => 'green'],
        ['month' => 'Febuari', 'value' => 100, 'color' => 'green'],
        ['month' => 'Maret', 'value' => 85, 'color' => 'red'],
        ['month' => 'April', 'value' => 95, 'color' => 'green'],
        ['month' => 'Mei', 'value' => 40, 'color' => 'red'],
        ['month' => 'Juni', 'value' => 75, 'color' => 'green'],
    ];

    // Data dummy Produk Terlaris (untuk Tabel)
    private $bestSellers = [
        ['product_name' => 'Batik Parang', 'units_sold' => 95, 'revenue' => '21.400.000,00'],
        ['product_name' => 'Batik Kawung', 'units_sold' => 82, 'revenue' => '19.400.000,00'],
        ['product_name' => 'Batik Mega Mendung', 'units_sold' => 75, 'revenue' => '17.000.000,00'],
        ['product_name' => 'Batik Sidomukti', 'units_sold' => 60, 'revenue' => '14.500.000,00'],
    ];

    /**
     * Menampilkan halaman Laporan Penjualan.
     */
    public function index()
    {
        return view('admin.pages.salesReport', [
            'monthlySales' => $this->monthlySales,
            'bestSellers' => $this->bestSellers,
            'activePage' => 'sales-report'
        ]);
    }
}