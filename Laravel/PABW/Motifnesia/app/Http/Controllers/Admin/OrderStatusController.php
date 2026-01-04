<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\DeliveryStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    /**
     * Menampilkan halaman status pengiriman.
     */
    public function index()
    {
        // Ambil semua orders dengan relasi
        $orders = Order::with(['user', 'orderItems.produk', 'metodePengiriman', 'metodePembayaran', 'deliveryStatus'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua delivery status untuk dropdown
        $deliveryStatuses = DeliveryStatus::all();

        return view('admin.pages.orderStatus', [
            'orders' => $orders,
            'deliveryStatuses' => $deliveryStatuses,
            'activePage' => 'order-status'
        ]);
    }

    /**
     * Update status pengiriman 
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'delivery_status_id' => 'required|exists:delivery_status,id'
        ]);

        $order = Order::findOrFail($id);
        $order->delivery_status_id = $request->delivery_status_id;
        $order->save();

        // Track status change history untuk notifikasi customer
        OrderStatusHistory::create([
            'order_id' => $order->id,
            'delivery_status_id' => $request->delivery_status_id,
            'changed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui'
        ]);
    }
}  