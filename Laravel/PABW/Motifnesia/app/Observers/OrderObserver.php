<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Notification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order)
    {
        // Buat notifikasi untuk order baru
        Notification::create([
            'user_id' => null, // Untuk admin
            'type' => 'order',
            'title' => 'Pesanan Baru #' . $order->id,
            'message' => 'Pesanan baru dari ' . $order->user->name . ' dengan total Rp ' . number_format($order->total_harga, 0, ',', '.'),
            'link' => '/admin/order-status', // Link ke halaman order status
            'priority' => 'important',
            'is_read' => false,
        ]);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        // Jika status pembayaran berubah jadi "Lunas"
        if ($order->isDirty('status_pembayaran') && $order->status_pembayaran === 'Lunas') {
            Notification::create([
                'user_id' => null,
                'type' => 'order',
                'title' => 'Pembayaran Dikonfirmasi #' . $order->id,
                'message' => 'Pembayaran untuk pesanan #' . $order->id . ' telah dikonfirmasi.',
                'link' => '/admin/order-status',
                'priority' => 'important',
                'is_read' => false,
            ]);
        }

        // Jika status pengiriman berubah
        if ($order->isDirty('status_pengiriman')) {
            $priority = 'normal';
            if ($order->status_pengiriman === 'Dibatalkan') {
                $priority = 'urgent';
            }

            Notification::create([
                'user_id' => null,
                'type' => 'order',
                'title' => 'Status Pesanan Berubah #' . $order->id,
                'message' => 'Status pengiriman pesanan #' . $order->id . ' menjadi: ' . $order->status_pengiriman,
                'link' => '/admin/order-status',
                'priority' => $priority,
                'is_read' => false,
            ]);
        }
    }
}
