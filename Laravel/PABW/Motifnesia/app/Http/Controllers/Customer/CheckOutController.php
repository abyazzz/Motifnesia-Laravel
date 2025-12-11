<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCard;
use App\Models\MetodePengiriman;
use App\Models\MetodePembayaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CheckOutController extends Controller
{
    /**
     * Tampilkan halaman checkout
     * Mengambil data dari session checkout_items dan DB
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil ID items yang dipilih dari session (disimpan saat checkout dari keranjang)
        $selectedIds = session('checkout_items');
        
        if (!$selectedIds || !is_array($selectedIds) || count($selectedIds) === 0) {
            return redirect()->route('customer.cart.index')->with('error', 'Tidak ada item yang dipilih untuk checkout.');
        }

        // Ambil data lengkap dari DB
        $cartItems = ShoppingCard::with('produk')
            ->where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Item tidak valid.');
        }

        // Ambil data user untuk alamat default
        $user = User::find(Auth::id());
        $defaultAddress = $this->formatAddress($user);

        // Ambil metode pengiriman dan pembayaran dari DB
        $metodePengiriman = MetodePengiriman::all();
        $metodePembayaran = MetodePembayaran::all();

        // Hitung subtotal produk
        $subtotalProduk = 0;
        $products = [];
        
        foreach ($cartItems as $item) {
            $harga = $item->produk->harga ?? 0;
            $qty = $item->qty;
            $subtotal = $harga * $qty;
            $subtotalProduk += $subtotal;

            $products[] = [
                'cart_id' => $item->id,
                'produk_id' => $item->product_id,
                'nama' => $item->produk->nama_produk ?? 'Produk',
                'gambar' => $item->produk->gambar ?? 'placeholder.jpg',
                'ukuran' => $item->ukuran,
                'qty' => $qty,
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];
        }

        return view('customer.pages.checkOut', compact(
            'products',
            'subtotalProduk',
            'defaultAddress',
            'metodePengiriman',
            'metodePembayaran'
        ));
    }

    /**
     * Proses checkout: Simpan data checkout ke session dan redirect ke payment
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'alamat' => 'required|string',
            'metode_pengiriman_id' => 'required|exists:metode_pengiriman,id',
            'metode_pembayaran_id' => 'required|exists:metode_pembayaran,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak lengkap.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil checkout_items dari session
        $selectedIds = session('checkout_items');
        
        if (!$selectedIds || !is_array($selectedIds)) {
            return response()->json([
                'success' => false,
                'message' => 'Session checkout tidak valid.'
            ], 400);
        }

        // Ambil data cart items dari DB
        $cartItems = ShoppingCard::with('produk')
            ->where('user_id', Auth::id())
            ->whereIn('id', $selectedIds)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan.'
            ], 404);
        }

        // Ambil data metode pengiriman dan pembayaran
        $metodePengiriman = MetodePengiriman::find($request->metode_pengiriman_id);
        $metodePembayaran = MetodePembayaran::find($request->metode_pembayaran_id);

        // Hitung total
        $subtotalProduk = 0;
        $products = [];

        foreach ($cartItems as $item) {
            $harga = $item->produk->harga ?? 0;
            $qty = $item->qty;
            $subtotal = $harga * $qty;
            $subtotalProduk += $subtotal;

            $products[] = [
                'cart_id' => $item->id,
                'produk_id' => $item->product_id,
                'nama' => $item->produk->nama_produk ?? 'Produk',
                'gambar' => $item->produk->gambar ?? 'placeholder.jpg',
                'ukuran' => $item->ukuran,
                'qty' => $qty,
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];
        }

        $totalOngkir = $metodePengiriman->harga ?? 0;
        $totalBayar = $subtotalProduk + $totalOngkir;

        // Simpan data checkout ke session
        $checkoutData = [
            'alamat' => $request->alamat,
            'metode_pengiriman' => [
                'id' => $metodePengiriman->id,
                'nama' => $metodePengiriman->nama_pengiriman,
                'deskripsi' => $metodePengiriman->deskripsi_pengiriman,
                'harga' => $metodePengiriman->harga,
            ],
            'metode_pembayaran' => [
                'id' => $metodePembayaran->id,
                'nama' => $metodePembayaran->nama_pembayaran,
                'deskripsi' => $metodePembayaran->deskripsi_pembayaran,
            ],
            'products' => $products,
            'subtotal_produk' => $subtotalProduk,
            'total_ongkir' => $totalOngkir,
            'total_bayar' => $totalBayar,
            'created_at' => now(),
        ];

        // Simpan ke session untuk digunakan di payment page
        session(['checkout_data' => $checkoutData]);

        return response()->json([
            'success' => true,
            'message' => 'Checkout berhasil!',
            'redirect_url' => route('customer.payment.index')
        ]);
    }

    /**
     * Format alamat user
     */
    private function formatAddress($user)
    {
        if (!$user) {
            return 'Alamat belum diatur';
        }

        $parts = array_filter([
            $user->address_line,
            $user->city,
            $user->province,
            $user->postal_code,
        ]);

        return !empty($parts) ? implode(', ', $parts) : 'Alamat belum diatur';
    }
}