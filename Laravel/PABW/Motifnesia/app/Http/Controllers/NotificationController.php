<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Tampilkan halaman Daftar Notifikasi.
     */
    public function index()
    {
        // Data Dummy untuk notifikasi sesuai screenshot
        $notifications = [
            [
                'status' => 'Pesanan kamu Sampai.',
                'timestamp' => '2025-06-11 22:03:25',
                'details' => [
                    ['nama' => 'Batik Mega Mendung (M)', 'harga' => 701522, 'jumlah' => 1],
                ],
                'icon_class' => 'fas fa-check-circle', // Contoh ikon status
            ],
            [
                'status' => 'Pesanan kamu Menunggu Konfirmasi.',
                'timestamp' => '2025-06-11 22:03:13',
                'details' => [
                    ['nama' => 'Batik Mega Mendung (M)', 'harga' => 701522, 'jumlah' => 1],
                ],
                'icon_class' => 'fas fa-clock',
            ],
            [
                'status' => 'Pesanan kamu Diproses.',
                'timestamp' => '2025-06-11 11:18:00',
                'details' => [
                    ['nama' => 'Batik Pisan Bali (XL)', 'harga' => 580598, 'jumlah' => 2],
                    ['nama' => 'Batik Lasem (S)', 'harga' => 185672, 'jumlah' => 1],
                ],
                'icon_class' => 'fas fa-cogs',
            ],
            [
                'status' => 'Pesanan kamu Dikirim.',
                'timestamp' => '2025-06-11 10:18:52',
                'details' => [
                    ['nama' => 'Batik Mega Mendung (M)', 'harga' => 701522, 'jumlah' => 1],
                ],
                'icon_class' => 'fas fa-truck',
            ],
        ];

        return view('notifications', compact('notifications'));
    }
}