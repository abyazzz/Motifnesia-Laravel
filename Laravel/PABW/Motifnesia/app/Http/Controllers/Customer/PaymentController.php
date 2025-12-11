<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\ShoppingCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman payment/transaksi
     * Menampilkan ringkasan dari session checkout_data
     */
    public function index()
    {
        if (!Auth::check()) {
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
        if (!Auth::check()) {
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
            // Generate order number (unique untuk grouping items)
            $orderNumber = 'ORD-' . time() . '-' . Auth::id();
            
            Log::info('Creating order:', ['order_number' => $orderNumber]);
            
            // 1. Create order (header) - 1 row untuk semua produk
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'alamat' => $checkoutData['alamat'],
                'metode_pengiriman_id' => $checkoutData['metode_pengiriman']['id'],
                'metode_pembayaran_id' => $checkoutData['metode_pembayaran']['id'],
                'delivery_status_id' => 1, // default: pending
                'total_ongkir' => $checkoutData['total_ongkir'],
                'total_bayar' => $checkoutData['total_bayar'],
                'payment_number' => $request->payment_number,
                'created_at' => $checkoutData['created_at'] ?? now(),
            ]);
            
            Log::info('Order created:', ['order_id' => $order->id]);
            
            // 2. Create order items (detail) - banyak row untuk banyak produk
            foreach ($checkoutData['products'] as $product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'produk_id' => $product['produk_id'],
                    'nama_produk' => $product['nama'],
                    'ukuran' => $product['ukuran'] ?? null,
                    'qty' => $product['qty'],
                    'harga' => $product['harga'],
                    'subtotal' => $product['subtotal'],
                ]);
                
                Log::info('Order item created:', ['order_id' => $order->id, 'product' => $product['nama']]);
            }

            // 3. Track status history (Pending - saat order dibuat)
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'delivery_status_id' => 1, // Pending
                'changed_at' => now(),
            ]);
            
            Log::info('Order status history created:', ['order_id' => $order->id, 'status' => 'Pending']);

            // Hapus item dari shopping cart setelah transaksi berhasil
            $cartIds = array_column($checkoutData['products'], 'cart_id');
            ShoppingCard::where('user_id', Auth::id())
                ->whereIn('id', $cartIds)
                ->delete();

            // Hapus session checkout
            session()->forget(['checkout_items', 'checkout_data']);

            DB::commit();

            // Redirect ke success page dengan order id
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
        if (!Auth::check()) {
            return redirect()->route('auth.login');
        }

        // Ambil detail order dengan items
        $order = Order::with(['orderItems', 'metodePengiriman', 'metodePembayaran', 'deliveryStatus'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('customer.home')->with('error', 'Transaksi tidak ditemukan.');
        }

        return view('customer.pages.transactionSuccess', compact('order'));
    }
}
