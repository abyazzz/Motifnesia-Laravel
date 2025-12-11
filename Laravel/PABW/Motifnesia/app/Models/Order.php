<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'alamat',
        'metode_pengiriman_id',
        'metode_pembayaran_id',
        'delivery_status_id',
        'total_ongkir',
        'total_bayar',
        'payment_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function metodePengiriman()
    {
        return $this->belongsTo(MetodePengiriman::class);
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    public function deliveryStatus()
    {
        return $this->belongsTo(DeliveryStatus::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
