<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCard;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiShoppingCardController extends Controller
{
    /**
     * ========================================
     * GET CART ITEMS (untuk user yang login)
     * ========================================
     * Endpoint: GET /api/cart
     * Headers: Authorization (nanti kalau pakai token)
     * 
     * Response includes:
     * - Cart items dengan detail produk
     * - Total harga keseluruhan
     * - Total items
     */
    public function index(Request $request)
    {
        try {
            // Untuk sekarang pakai user_id dari request
            // Nanti kalau udah pakai Sanctum: $userId = $request->user()->id;
            
            $userId = $request->input('user_id');
            
            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID required'
                ], 401);
            }

            // Ambil semua cart items user dengan relasi produk
            $cartItems = ShoppingCard::with('produk')
                ->where('user_id', $userId)
                ->get();

            // Format data untuk response
            $items = [];
            $totalPrice = 0;
            $totalItems = 0;

            foreach ($cartItems as $cart) {
                if ($cart->produk) {
                    $productPrice = $cart->produk->harga_diskon ?? $cart->produk->harga;
                    $subtotal = $productPrice * $cart->qty;
                    $totalPrice += $subtotal;
                    $totalItems += $cart->qty;

                    $items[] = [
                        'cart_id' => $cart->id,
                        'product_id' => $cart->product_id,
                        'product_name' => $cart->produk->nama_produk,
                        'product_image' => $cart->produk->gambar,
                        'product_price' => $productPrice,
                        'product_original_price' => $cart->produk->harga,
                        'ukuran' => $cart->ukuran,
                        'qty' => $cart->qty,
                        'subtotal' => $subtotal,
                        'stock' => $cart->produk->stok,
                        'kategori' => $cart->produk->kategori,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Cart berhasil diambil',
                'data' => [
                    'items' => $items,
                    'summary' => [
                        'total_items' => $totalItems,
                        'total_price' => $totalPrice,
                        'currency' => 'IDR'
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================
     * ADD TO CART
     * ========================================
     * Endpoint: POST /api/cart
     * Body:
     *   - user_id (required) - nanti ganti pakai token
     *   - product_id (required)
     *   - ukuran (required)
     *   - qty (optional, default: 1)
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:produk,id',
                'ukuran' => 'required|string',
                'qty' => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userId = $request->user_id;
            $productId = $request->product_id;
            $ukuran = $request->ukuran;
            $qty = $request->qty ?? 1;

            // Cek stok produk
            $produk = Produk::find($productId);
            if (!$produk) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            if ($produk->stok < $qty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi',
                    'available_stock' => $produk->stok
                ], 422);
            }

            // Cek apakah produk + ukuran sudah ada di cart
            $existingCart = ShoppingCard::where('user_id', $userId)
                ->where('product_id', $productId)
                ->where('ukuran', $ukuran)
                ->first();

            if ($existingCart) {
                // Update qty jika sudah ada
                $newQty = $existingCart->qty + $qty;
                
                // Cek stok lagi untuk qty baru
                if ($produk->stok < $newQty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk qty yang diminta',
                        'available_stock' => $produk->stok,
                        'current_cart_qty' => $existingCart->qty
                    ], 422);
                }

                $existingCart->qty = $newQty;
                $existingCart->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Jumlah produk di cart berhasil diupdate',
                    'data' => [
                        'cart_id' => $existingCart->id,
                        'product_name' => $produk->nama_produk,
                        'qty' => $existingCart->qty
                    ]
                ], 200);
            } else {
                // Tambah item baru ke cart
                $cart = ShoppingCard::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'ukuran' => $ukuran,
                    'qty' => $qty,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Produk berhasil ditambahkan ke cart',
                    'data' => [
                        'cart_id' => $cart->id,
                        'product_name' => $produk->nama_produk,
                        'ukuran' => $ukuran,
                        'qty' => $qty
                    ]
                ], 201);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ke cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================
     * UPDATE CART ITEM QTY
     * ========================================
     * Endpoint: PUT/PATCH /api/cart/{cart_id}
     * Body:
     *   - user_id (required) - untuk validasi ownership
     *   - qty (required)
     */
    public function update(Request $request, $cartId)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'qty' => 'required|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userId = $request->user_id;
            $qty = $request->qty;

            // Cari cart item & pastikan milik user
            $cart = ShoppingCard::where('id', $cartId)
                ->where('user_id', $userId)
                ->with('produk')
                ->first();

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item tidak ditemukan'
                ], 404);
            }

            // Cek stok produk
            if ($cart->produk->stok < $qty) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi',
                    'available_stock' => $cart->produk->stok
                ], 422);
            }

            // Update qty
            $cart->qty = $qty;
            $cart->save();

            // Hitung subtotal baru
            $productPrice = $cart->produk->harga_diskon ?? $cart->produk->harga;
            $subtotal = $productPrice * $qty;

            return response()->json([
                'success' => true,
                'message' => 'Jumlah produk berhasil diupdate',
                'data' => [
                    'cart_id' => $cart->id,
                    'product_name' => $cart->produk->nama_produk,
                    'qty' => $qty,
                    'subtotal' => $subtotal
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================
     * DELETE CART ITEM
     * ========================================
     * Endpoint: DELETE /api/cart/{cart_id}
     * Query param:
     *   - user_id (required) - untuk validasi ownership
     */
    public function destroy(Request $request, $cartId)
    {
        try {
            $userId = $request->input('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID required'
                ], 401);
            }

            // Cari cart item & pastikan milik user
            $cart = ShoppingCard::where('id', $cartId)
                ->where('user_id', $userId)
                ->first();

            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item tidak ditemukan'
                ], 404);
            }

            $cart->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari cart'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus item dari cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================
     * CLEAR ALL CART
     * ========================================
     * Endpoint: DELETE /api/cart/clear
     * Body:
     *   - user_id (required)
     */
    public function clear(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID required'
                ], 401);
            }

            // Hapus semua cart items user
            $deletedCount = ShoppingCard::where('user_id', $userId)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart berhasil dikosongkan',
                'deleted_items' => $deletedCount
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengosongkan cart',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ========================================
     * GET CART COUNT (jumlah item di cart)
     * ========================================
     * Endpoint: GET /api/cart/count
     * Query param:
     *   - user_id (required)
     */
    public function count(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            if (!$userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID required'
                ], 401);
            }

            $count = ShoppingCard::where('user_id', $userId)->count();
            $totalQty = ShoppingCard::where('user_id', $userId)->sum('qty');

            return response()->json([
                'success' => true,
                'message' => 'Cart count berhasil diambil',
                'data' => [
                    'total_items' => $count,      // Berapa jenis produk
                    'total_quantity' => $totalQty // Total qty semua produk
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil cart count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
