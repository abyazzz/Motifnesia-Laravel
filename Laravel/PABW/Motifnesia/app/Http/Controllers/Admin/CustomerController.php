<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Data dummy Pelanggan
    private $customers = [
        ['id' => 1, 'name' => 'User Pertama', 'email' => 'user1@gmail.com'],
        ['id' => 2, 'name' => 'User Kedua', 'email' => 'user2@gmail.com'],
        ['id' => 3, 'name' => 'Budi Santoso', 'email' => 'budi.s@gmail.com'],
        ['id' => 4, 'name' => 'Citra Dewi', 'email' => 'citra_d@gmail.com'],
        ['id' => 5, 'name' => 'Doni Irawan', 'email' => 'doni.i@gmail.com'],
        ['id' => 6, 'name' => 'Evi Yulianti', 'email' => 'evi.y@gmail.com'],
        ['id' => 7, 'name' => 'Ferry Aji', 'email' => 'ferry.aji@gmail.com'],
        ['id' => 8, 'name' => 'Gita Kusuma', 'email' => 'gita.k@gmail.com'],
        ['id' => 9, 'name' => 'Hadi Widodo', 'email' => 'hadi.w@gmail.com'],
        ['id' => 10, 'name' => 'Indah Lestari', 'email' => 'indah.l@gmail.com'],
        ['id' => 11, 'name' => 'Joko Susilo', 'email' => 'joko.s@gmail.com'],
        ['id' => 12, 'name' => 'Karin Putri', 'email' => 'karin.p@gmail.com'],
    ];

    /**
     * Menampilkan daftar pelanggan.
     */
    public function index()
    {
        return view('admin.pages.customerList', [
            'customers' => $this->customers,
            'activePage' => 'customers'
        ]);
    }
}