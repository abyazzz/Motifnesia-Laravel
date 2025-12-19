<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetail;
use Illuminate\Support\Facades\DB;

class PaymentConfirmationController extends Controller
{
    // Fungsi untuk proses pembayaran final
    public function storeFinalOrder(Request $request)
    {
        $session = session('session_checkout_final');
        $user = session('user');
        $userId = $user['id'] ?? null;
        if (!$userId || !$session) {
            return redirect()->route('customer.checkout.index');
        }

        DB::beginTransaction();
        try {
            $order = ProductOrder::create([
                'user_id' => $userId,
                'alamat' => $session['alamat'],
                'metode_pengiriman_id' => $session['metode_pengiriman'],
                'metode_pembayaran_id' => $session['metode_pembayaran'],
                'total' => $session['total'],
                'status' => 'pending',
            ]);

            foreach ($session['produk'] as $item) {
                $harga = $item['produk']['harga_diskon'] ?? $item['produk']['harga'];
                ProductOrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['produk']['id'],
                    'ukuran' => $item['ukuran'],
                    'qty' => $item['qty'],
                    'harga' => $harga,
                ]);
            }

            // Bersihkan session checkout
            session()->forget(['session_checkout', 'session_checkout_final']);
            DB::commit();
            return redirect()->route('customer.payment.success');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan.');
        }
    }

    // Halaman success
    public function success()
    {
        return view('customer.pages.paymentSuccess');
    }
}
