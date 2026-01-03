<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderReview;

class CustomerReviewController extends Controller
{
    // GET: ambil ulasan berdasarkan produk
    public function index($produk_id)
    {
        $reviews = OrderReview::where('produk_id', $produk_id)
            ->select('rating', 'deskripsi_ulasan', 'created_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    // POST: tambah ulasan (VERSI SEDERHANA)
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required',
            'rating' => 'required|integer|min:1|max:5',
            'deskripsi_ulasan' => 'nullable'
        ]);

        $review = OrderReview::create([
            'user_id' => 2,        // dummy
            'order_id' => 1,       // dummy
            'order_item_id' => 1,  // dummy
            'produk_id' => $request->produk_id,
            'rating' => $request->rating,
            'deskripsi_ulasan' => $request->deskripsi_ulasan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil ditambahkan',
            'data' => $review
        ], 201);
    }
}
