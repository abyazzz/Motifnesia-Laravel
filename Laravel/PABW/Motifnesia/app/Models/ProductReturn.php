<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'user_id',
        'order_id',
        'order_item_id',
        'produk_id',
        'reason',
        'description',
        'photo_proof',
        'status',
        'admin_note',
        'action_type',
        'refund_amount',
        'refund_status',
    ];

    protected $casts = [
        'refund_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Order
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi ke OrderItem
     */
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Relasi ke Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'Pending' => 'orange',
            'Disetujui' => 'green',
            'Ditolak' => 'red',
            'Diproses' => 'blue',
            'Selesai' => 'green',
            default => 'gray'
        };
    }

    /**
     * Get relative time
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if return is still within valid period (7 days from order received)
     */
    public static function canReturnOrder($orderId)
    {
        $order = Order::find($orderId);
        
        if (!$order || $order->delivery_status_id != 5) {
            return false;
        }

        // Check if order was received within last 7 days
        $receivedDate = $order->updated_at; // Assuming updated_at is when status changed to Sampai
        $daysSinceReceived = Carbon::now()->diffInDays($receivedDate);
        
        return $daysSinceReceived <= 7;
    }

    /**
     * Check if item already has return request
     */
    public static function hasReturnRequest($orderItemId)
    {
        return self::where('order_item_id', $orderItemId)
            ->whereIn('status', ['Pending', 'Disetujui', 'Diproses'])
            ->exists();
    }
}
