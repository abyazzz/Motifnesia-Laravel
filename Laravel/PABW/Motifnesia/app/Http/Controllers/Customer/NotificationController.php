<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\OrderStatusHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Tampilkan halaman notifikasi
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil history status order user
        // Eager load order + orderItems + deliveryStatus
        $notifications = OrderStatusHistory::with(['order.orderItems', 'deliveryStatus'])
            ->whereHas('order', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('changed_at', 'desc')
            ->get()
            ->groupBy('deliveryStatus.nama_status');

        return view('customer.pages.notifications', compact('notifications'));
    }
}