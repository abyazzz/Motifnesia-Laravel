<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShoppingCard;
use App\Models\Produk;

class ShoppingCardController extends Controller
{
    // Tambah ke keranjang
    public function add(Request $request)
    {
        $user = session('user');
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
        }
        $userId = $user['id'] ?? null;
        if (!$userId) {
            // Fallback: cari user id dari email/username di DB
            if (isset($user['email'])) {
                $dbUser = \App\Models\User::where('email', $user['email'])->first();
                $userId = $dbUser ? $dbUser->id : null;
            } elseif (isset($user['username'])) {
                $dbUser = \App\Models\User::where('name', $user['username'])->first();
                $userId = $dbUser ? $dbUser->id : null;
            }
        }
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'User tidak valid.']);
        }
        $productId = $request->input('product_id');
        $ukuran = $request->input('ukuran');

        // Cek apakah produk+ukuran sudah ada di keranjang user
        $item = ShoppingCard::where('user_id', $userId)
            ->where('product_id', $productId)
            ->where('ukuran', $ukuran)
            ->first();

        if ($item) {
            $item->qty += 1;
            $item->save();
        } else {
            ShoppingCard::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'ukuran' => $ukuran,
                'qty' => 1,
            ]);
        }
        return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang!']);
    }

    // Tampilkan isi keranjang user
    public function index()
    {
        $sessionUser = session('user');
        $userId = $sessionUser['id'] ?? null;
        if (!$userId) {
            // Fallback: cari user id dari email/username di DB
            if (isset($sessionUser['email'])) {
                $user = \App\Models\User::where('email', $sessionUser['email'])->first();
                $userId = $user ? $user->id : null;
            }
        }
        if (!$userId) {
            return redirect()->route('auth.login');
        }
        $items = ShoppingCard::with('produk')
            ->where('user_id', $userId)
            ->get();
        return view('customer.pages.shoppingCard', compact('items'));
    }
}
