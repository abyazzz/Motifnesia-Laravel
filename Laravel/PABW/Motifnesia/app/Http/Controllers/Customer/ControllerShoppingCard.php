<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShoppingCard;
use App\Models\Produk;

class ControllerShoppingCard extends Controller
{
    public function index()
    {
        $user = session('user');
        $userId = $user['id'] ?? null;

        if (!$userId) {
            return redirect()->route('auth.login');
        }

        // Ambil keranjang user
        $cards = ShoppingCard::with('produk')
            ->where('user_id', $userId)
            ->get();

        // Hitung total
        $total = $cards->sum(function ($item) {
            return $item->produk->harga * $item->qty;
        });

        return view('customer.pages.shoppingCart', compact('cards', 'total'));
    }

    public function updateQty(Request $request, $id)
    {
        $card = ShoppingCard::findOrFail($id);

        $qty = (int) $request->qty;
        if ($qty < 1) $qty = 1;

        $card->qty = $qty;
        $card->save();

        return back();
    }

    public function deleteItem($id)
    {
        ShoppingCard::where('id', $id)->delete();
        return back();
    }

    public function storeCheckoutSession(Request $request)
    {
        $selected = $request->input('selected_ids', []);
        if (empty($selected)) {
            return back()->with('error', 'Pilih produk terlebih dahulu.');
        }

        $user = session('user');
        $userId = $user['id'] ?? null;

        $cards = ShoppingCard::with('produk')
            ->where('user_id', $userId)
            ->whereIn('id', $selected)
            ->get();

        $products = [];
        foreach ($cards as $item) {
            $products[] = [
                'shopping_card_id' => $item->id,
                'produk_id'        => $item->produk->id,
                'nama'             => $item->produk->nama_produk,
                'harga'            => (int) $item->produk->harga,
                'qty'              => (int) $item->qty,
                'subtotal'         => (int) $item->produk->harga * (int) $item->qty,
                'ukuran'           => $item->ukuran,
            ];
        }

        session(['session_checkout' => $products]);

        return redirect()->route('customer.checkout.index');
    }
}
