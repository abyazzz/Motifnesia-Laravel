<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderReview;
use App\Models\Produk;

class PurchaseHistoryController extends Controller
{
    /**
     * Get purchase history data for authenticated user
     * Returns all checkout items where order status = 'Sampai' (status_id = 4)
     */
    public static function getHistoryData()
    {
        $userId = Auth::id();

        if (!$userId) {
            return [];
        }

        // Get all order items from orders that belong to this user
        // Include all orders regardless of status for history, but only enable review button if status = Sampai (4)
        $orderItems = OrderItem::with(['produk', 'order.deliveryStatus', 'review', 'productReturns'])
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('id', 'desc')
            ->get();

        $historyData = [];

        foreach ($orderItems as $item) {
            $order = $item->order;
            $produk = $item->produk;
            $status = $order->deliveryStatus;
            $review = $item->review;

            // Determine if user can review (status = Sampai/5 and not reviewed yet)
            $canReview = ($order->delivery_status_id == 5) && !$review;
            $hasReviewed = $review !== null;

            // Check if this item has a return request
            $hasReturn = $item->productReturns()->exists();

            $historyData[] = [
                'order_item_id' => $item->id,
                'order_id' => $order->id,
                'produk_id' => $item->produk_id,
                'nama' => $produk->nama_produk ?? 'Produk',
                'gambar' => $produk->gambar ?? 'placeholder.jpg',
                'ukuran' => $item->ukuran,
                'qty' => $item->qty,
                'harga' => $item->harga,
                'subtotal' => $item->subtotal,
                'status_nama' => $status->nama_status ?? 'Menunggu Konfirmasi',
                'status_id' => $order->delivery_status_id,
                'can_review' => $canReview,
                'has_reviewed' => $hasReviewed,
                'status_ulasan' => $hasReviewed ? 'lihat' : ($canReview ? 'beri' : 'disabled'),
                'has_return' => $hasReturn,
            ];
        }

        return $historyData;
    }
}