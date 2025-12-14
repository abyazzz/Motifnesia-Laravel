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
        // Notifikasi untuk admin
        Notification::create([
            'user_id' => null, // Untuk admin
            'type' => 'order',
            'title' => 'Pesanan Baru #' . $order->id,
            'message' => 'Pesanan baru dari ' . $order->user->name . ' dengan total Rp ' . number_format($order->total_bayar, 0, ',', '.'),
            'link' => '/admin/order-status',
            'priority' => 'important',
            'is_read' => false,
        ]);

        // Notifikasi untuk customer
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order',
            'title' => 'Status Pesanan Anda: Pending',
            'message' => 'Pesanan #' . $order->id . ' dengan total Rp ' . number_format($order->total_bayar, 0, ',', '.') . ' menunggu konfirmasi pembayaran.',
            'link' => '/customer/order-history',
            'priority' => 'important',
            'is_read' => false,
        ]);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order)
    {
        // Jika status pengiriman berubah
        if ($order->isDirty('delivery_status_id')) {
            // Load relasi deliveryStatus jika belum di-load
            $order->load('deliveryStatus');
            $statusName = $order->deliveryStatus ? $order->deliveryStatus->nama_status : 'Unknown';
            $priority = 'normal';
            
            // Set priority berdasarkan status
            if (strtolower($statusName) === 'pending') {
                $priority = 'important';
            } elseif (in_array(strtolower($statusName), ['diproses', 'dikemas'])) {
                $priority = 'normal';
            } elseif (strtolower($statusName) === 'dalam perjalanan') {
                $priority = 'important';
            } elseif (strtolower($statusName) === 'sampai') {
                $priority = 'info';
            }

            // Notifikasi untuk admin
            Notification::create([
                'user_id' => null,
                'type' => 'order',
                'title' => 'Status Pesanan Berubah #' . $order->id,
                'message' => 'Status pengiriman pesanan #' . $order->id . ' menjadi: ' . $statusName,
                'link' => '/admin/order-status',
                'priority' => $priority,
                'is_read' => false,
            ]);

            // Notifikasi untuk customer
            Notification::create([
                'user_id' => $order->user_id,
                'type' => 'order',
                'title' => 'Status Pesanan Anda: ' . $statusName,
                'message' => 'Status pengiriman pesanan #' . $order->id . ' telah diperbarui menjadi: ' . $statusName,
                'link' => '/customer/order-history',
                'priority' => $priority,
                'is_read' => false,
            ]);
        }
    }
}
