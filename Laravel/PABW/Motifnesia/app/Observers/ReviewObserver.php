<?php

namespace App\Observers;

use App\Models\OrderReview;
use App\Models\Notification;

class ReviewObserver
{
    /**
     * Handle the OrderReview "created" event.
     */
    public function created(OrderReview $review)
    {
        // Tentukan priority berdasarkan rating
        $priority = 'info';
        if ($review->rating <= 2) {
            $priority = 'urgent'; // Rating rendah butuh perhatian
        } elseif ($review->rating == 3) {
            $priority = 'important';
        }

        // Buat notifikasi untuk review baru
        Notification::create([
            'user_id' => null, // Untuk admin
            'type' => 'review',
            'title' => 'Ulasan Baru: ' . $review->produk->nama_produk,
            'message' => 'Rating ' . $review->rating . ' bintang dari ' . $review->user->name . ': "' . substr($review->komentar, 0, 100) . (strlen($review->komentar) > 100 ? '...' : '') . '"',
            'link' => '/admin/product-reviews',
            'priority' => $priority,
            'is_read' => false,
        ]);
    }
}
