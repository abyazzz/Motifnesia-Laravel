<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Menampilkan daftar pelanggan yang pernah membeli produk.
     */
    public function index()
    {
        // Ambil user yang punya minimal 1 order (pernah checkout)
        $customers = User::whereHas('orders')
            ->withCount([
                'orders as total_products' => function ($query) {
                    $query->join('order_items', 'orders.id', '=', 'order_items.order_id')
                          ->select(DB::raw('SUM(order_items.qty)'));
                }
            ])
            ->with(['orders.orderItems.produk'])
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->name,
                    'full_name' => $user->full_name ?? $user->name,
                    'email' => $user->email,
                    'total_products' => $user->total_products ?? 0,
                    'orders' => $user->orders
                ];
            });

        return view('admin.pages.customerList', [
            'customers' => $customers,
            'activePage' => 'customers'
        ]);
    }

    /**
     * Hapus customer berdasarkan ID.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.customers.index')
                        ->with('success', 'Pelanggan berhasil dihapus.');
    }
}