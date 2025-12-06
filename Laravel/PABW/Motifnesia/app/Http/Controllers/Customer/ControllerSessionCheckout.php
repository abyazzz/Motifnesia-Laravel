<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ShoppingCard;
use App\Models\MetodePengiriman;
use App\Models\MetodePembayaran;

class ControllerSessionCheckout extends Controller
{
    // Simpan produk yang di-checkout ke session_checkout
    public function storeCheckoutSession(Request $request)
    {
        $user = session('user');
        $userId = $user['id'] ?? null;
        if (!$userId) {
            return redirect()->route('auth.login');
        }
        // Ambil produk yang dipilih dari request (array of shopping_card_id)
        $selectedIds = $request->input('selected_ids', []);
        $products = ShoppingCard::with('produk')
            ->where('user_id', $userId)
            ->whereIn('id', $selectedIds)
            ->get();
        session(['session_checkout' => $products->toArray()]);
        return redirect()->route('customer.checkout.index');
    }

    // Tampilkan halaman checkout
    public function index()
    {
        $user = session('user');
        $userId = $user['id'] ?? null;
        if (!$userId) {
            return redirect()->route('auth.login');
        }
        $alamat = User::find($userId)->address_line ?? '';
        $products = session('session_checkout', []);
        $metodePengiriman = MetodePengiriman::all();
        $metodePembayaran = MetodePembayaran::all();
        return view('customer.pages.checkOut', compact('alamat', 'products', 'metodePengiriman', 'metodePembayaran'));
    }

    // Simpan data final checkout ke session_checkout_final
    public function storeCheckoutFinal(Request $request)
    {
        $data = $request->only(['alamat', 'produk', 'metode_pengiriman', 'metode_pembayaran', 'total']);
        session(['session_checkout_final' => $data]);
        // Nanti redirect ke halaman pembayaran
        return redirect()->route('customer.payment.index');
    }
}
