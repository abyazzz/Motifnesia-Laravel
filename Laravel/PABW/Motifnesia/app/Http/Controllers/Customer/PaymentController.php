<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use App\Models\ProductOrderItem;
use App\Models\ShoppingCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Helper: Get authenticated user ID
     */
    private function getUserId()
    {
        $user = session('user');
        if (!$user) {
            return null;
        }

        $userId = $user['id'] ?? null;
        
        if (!$userId) {
            if (isset($user['email'])) {
                $dbUser = User::where('email', $user['email'])->first();
                $userId = $dbUser ? $dbUser->id : null;
            } elseif (isset($user['username'])) {
                $dbUser = User::where('name', $user['username'])->first();
                $userId = $dbUser ? $dbUser->id : null;
            }
        }

        return $userId;
    }

    /**
     * Tampilkan halaman payment/transaksi
     * Menampilkan ringkasan dari session checkout_data
     */
    public function index()
    {
        $userId = $this->getUserId();
        
        if (!$userId) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data checkout dari session
        $checkoutData = session('checkout_data');
        
        if (!$checkoutData) {
            return redirect()->route('customer.cart.index')->with('error', 'Data checkout tidak ditemukan. Silakan checkout ulang.');
        }

        // Generate payment deadline (24 jam dari sekarang)
        $paymentDeadline = now()->addHours(24);

        return view('customer.pages.payment', compact('checkoutData', 'paymentDeadline'));
    }

    /**
     * Proses pembayaran dan simpan transaksi ke database
     */
    public function store(Request $request)
    {
        $userId = $this->getUserId();
        
        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Validasi nomor pembayaran
        $validator = Validator::make($request->all(), [
            'payment_number' => 'required|string|min:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Nomor pembayaran tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil data checkout dari session
        $checkoutData = session('checkout_data');
        
        // Debug: Log session data
        Log::info('Checkout Data from Session:', ['data' => $checkoutData]);
        
        if (!$checkoutData) {
            return response()->json([
                'success' => false,
                'message' => 'Data checkout tidak ditemukan. Session mungkin expired.'
            ], 400);
        }

        DB::beginTransaction();
        
        try {
            // Simpan order ke database
            $order = ProductOrder::create([
                'user_id' => $userId,
                'alamat' => $checkoutData['alamat'],
                'metode_pengiriman_id' => $checkoutData['metode_pengiriman']['id'],
                'metode_pembayaran_id' => $checkoutData['metode_pembayaran']['id'],
                'subtotal_produk' => $checkoutData['subtotal_produk'],
                'total_ongkir' => $checkoutData['total_ongkir'],
                'total_bayar' => $checkoutData['total_bayar'],
                'payment_number' => $request->payment_number,
                'status' => 'pending', // Status awal: menunggu konfirmasi admin
                'created_at' => $checkoutData['created_at'] ?? now(),
            ]);

            // Simpan order items
            foreach ($checkoutData['products'] as $product) {
                ProductOrderItem::create([
                    'product_order_id' => $order->id,
                    'produk_id' => $product['produk_id'],
                    'nama' => $product['nama'],
                    'harga' => $product['harga'],
                    'qty' => $product['qty'],
                    'subtotal' => $product['subtotal'],
                    'ukuran' => $product['ukuran'] ?? null,
                ]);
            }

            // Hapus item dari shopping cart setelah transaksi berhasil
            $cartIds = array_column($checkoutData['products'], 'cart_id');
            ShoppingCard::where('user_id', $userId)
                ->whereIn('id', $cartIds)
                ->delete();

            // Hapus session checkout
            session()->forget(['checkout_items', 'checkout_data']);

            DB::commit();

            // Redirect ke success page
            return redirect()->route('customer.transaction.success', $order->id)
                ->with('success', 'Transaksi berhasil! Menunggu konfirmasi pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log error untuk debugging
            Log::error('Payment Error: ' . $e->getMessage());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            // Redirect back dengan error message
            return redirect()->back()
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Halaman sukses setelah transaksi
     */
    public function success($orderId)
    {
        $userId = $this->getUserId();
        
        if (!$userId) {
            return redirect()->route('auth.login');
        }

        // Ambil detail order
        $order = ProductOrder::with(['details', 'metodePengiriman', 'metodePembayaran'])
            ->where('id', $orderId)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return redirect()->route('customer.home')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('customer.pages.transactionSuccess', compact('order'));
    }
}
