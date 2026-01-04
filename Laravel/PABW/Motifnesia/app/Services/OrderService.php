<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\ShoppingCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService
{
    /**
     * Create order dari checkout data
     * 
     * @param array $checkoutData
     * @param string $paymentNumber
     * @return Order
     * @throws \Exception
     */
    public function createOrder(array $checkoutData, string $paymentNumber): Order
    {
        DB::beginTransaction();
        
        try {
            // Generate order number (unique untuk grouping items)
            $orderNumber = $this->generateOrderNumber();
            
            Log::info('Creating order:', ['order_number' => $orderNumber]);
            
            // 1. Create order (header)
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'alamat' => $checkoutData['alamat'],
                'metode_pengiriman_id' => $checkoutData['metode_pengiriman']['id'],
                'metode_pembayaran_id' => $checkoutData['metode_pembayaran']['id'],
                'delivery_status_id' => config('order.delivery_status.pending', 1),
                'total_ongkir' => $checkoutData['total_ongkir'],
                'total_bayar' => $checkoutData['total_bayar'],
                'payment_number' => $paymentNumber,
                'created_at' => $checkoutData['created_at'] ?? now(),
            ]);
            
            Log::info('Order created:', ['order_id' => $order->id]);
            
            // 2. Create order items (detail)
            $this->createOrderItems($order, $checkoutData['products']);
            
            // 3. Track status history
            $this->createStatusHistory($order->id, config('order.delivery_status.pending', 1));
            
            // 4. Clear shopping cart
            $this->clearCart($checkoutData['products']);
            
            DB::commit();
             
            return $order;
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Creation Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Create order items
     */
    private function createOrderItems(Order $order, array $products): void
    {
        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'produk_id' => $product['produk_id'],
                'nama_produk' => $product['nama'],
                'ukuran' => $product['ukuran'] ?? null,
                'qty' => $product['qty'],
                'harga' => $product['harga'],
                'subtotal' => $product['subtotal'],
            ]);
            
            Log::info('Order item created:', [
                'order_id' => $order->id, 
                'product' => $product['nama']
            ]);
        }
    }
    
    /**
     * Create status history
     */
    private function createStatusHistory(int $orderId, int $statusId): void
    {
        OrderStatusHistory::create([
            'order_id' => $orderId,
            'delivery_status_id' => $statusId,
            'changed_at' => now(),
        ]);
        
        Log::info('Order status history created:', [
            'order_id' => $orderId, 
            'status_id' => $statusId
        ]);
    }
    
    /**
     * Clear shopping cart items
     */
    private function clearCart(array $products): void
    {
        $cartIds = array_column($products, 'cart_id');
        ShoppingCard::where('user_id', Auth::id())
            ->whereIn('id', $cartIds)
            ->delete();
    }
    
    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        $prefix = config('order.order_number_prefix', 'ORD');
        return $prefix . '-' . time() . '-' . Auth::id();
    }
    
    /**
     * Calculate order totals
     * 
     * @param array $products
     * @param float $shippingCost
     * @return array
     */
    public function calculateTotals(array $products, float $shippingCost): array
    {
        $subtotal = 0;
        
        foreach ($products as $product) {
            $subtotal += $product['subtotal'];
        }
        
        return [
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $subtotal + $shippingCost,
        ];
    }
}
