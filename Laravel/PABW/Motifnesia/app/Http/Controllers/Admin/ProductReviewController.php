<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\OrderReview;
use Illuminate\Support\Facades\DB;

class ProductReviewController extends Controller
{
    /**
     * Menampilkan halaman ulasan produk.
     * Tampilkan semua produk dengan filter rating.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, highest, lowest
        
        // Ambil semua produk
        $query = Produk::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with(['reviews.user', 'reviews.order']);
        
        // Apply filter
        if ($filter === 'highest') {
            // Rating tertinggi: yang ada review dulu (desc), yang 0 di bawah
            $query->orderByRaw('CASE WHEN reviews_count > 0 THEN 0 ELSE 1 END')
                  ->orderByDesc('reviews_avg_rating');
        } elseif ($filter === 'lowest') {
            // Rating terendah: yang ada review dulu (asc), yang 0 tetep di bawah
            $query->orderByRaw('CASE WHEN reviews_count > 0 THEN 0 ELSE 1 END')
                  ->orderBy('reviews_avg_rating');
        } else {
            $query->orderBy('id', 'desc'); // Default: semua produk terbaru
        }
        
        $products = $query->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'nama_produk' => $product->nama_produk,
                'gambar' => $product->gambar,
                'average_rating' => round($product->reviews_avg_rating ?? 0, 1),
                'total_reviews' => $product->reviews_count ?? 0,
                'has_reviews' => $product->reviews_count > 0,
                'reviews' => $product->reviews->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'customer_name' => $review->user->full_name ?? $review->user->name ?? 'Guest',
                        'rating' => $review->rating,
                        'comment' => $review->deskripsi_ulasan,
                        'date' => $review->created_at->format('d M Y'),
                        'order_number' => $review->order->order_number ?? '-',
                    ];
                })
            ];
        });

        return view('admin.pages.productReviews', [
            'products' => $products,
            'activePage' => 'reviews',
            'currentFilter' => $filter
        ]);
    }
}