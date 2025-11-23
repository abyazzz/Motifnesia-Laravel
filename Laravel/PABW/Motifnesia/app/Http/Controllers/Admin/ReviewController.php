<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Data dummy Ulasan (Review)
    private $reviews = [
        ['id' => 1, 'user_name' => 'User Pertama', 'user_email' => 'user1@gmail.com', 'rating' => 5.0, 'product_id' => 1],
        ['id' => 2, 'user_name' => 'User Kedua', 'user_email' => 'user2@gmail.com', 'rating' => 5.0, 'product_id' => 2],
        ['id' => 3, 'user_name' => 'Budi S.', 'user_email' => 'budi.s@gmail.com', 'rating' => 5.0, 'product_id' => 3],
        ['id' => 4, 'user_name' => 'Citra D.', 'user_email' => 'citra_d@gmail.com', 'rating' => 5.0, 'product_id' => 4],
        ['id' => 5, 'user_name' => 'Doni I.', 'user_email' => 'doni.i@gmail.com', 'rating' => 5.0, 'product_id' => 5],
        ['id' => 6, 'user_name' => 'Evi Y.', 'user_email' => 'evi.y@gmail.com', 'rating' => 5.0, 'product_id' => 6],
        ['id' => 7, 'user_name' => 'Ferry A.', 'user_email' => 'ferry.aji@gmail.com', 'rating' => 5.0, 'product_id' => 7],
        ['id' => 8, 'user_name' => 'Gita K.', 'user_email' => 'gita.k@gmail.com', 'rating' => 5.0, 'product_id' => 8],
        ['id' => 9, 'user_name' => 'Hadi W.', 'user_email' => 'hadi.w@gmail.com', 'rating' => 5.0, 'product_id' => 9],
        ['id' => 10, 'user_name' => 'Indah L.', 'user_email' => 'indah.l@gmail.com', 'rating' => 5.0, 'product_id' => 10],
        ['id' => 11, 'user_name' => 'Joko S.', 'user_email' => 'joko.s@gmail.com', 'rating' => 5.0, 'product_id' => 11],
        ['id' => 12, 'user_name' => 'Karin P.', 'user_email' => 'karin.p@gmail.com', 'rating' => 5.0, 'product_id' => 12],
    ];

    /**
     * Menampilkan halaman moderasi ulasan.
     */
    public function index()
    {
        return view('admin.pages.reviewModeration', [
            'reviews' => $this->reviews,
            'activePage' => 'reviews'
        ]);
    }
}