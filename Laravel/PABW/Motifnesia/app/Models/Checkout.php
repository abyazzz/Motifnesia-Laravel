<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $table = 'checkout';
    
    protected $fillable = [
        'user_id',
        'alamat',
        'metode_pengiriman_id',
        'metode_pembayaran_id',
        'delivery_status_id',
        'produk_id',
        'nama_produk',
        'ukuran',
        'qty',
        'harga',
        'subtotal',
        'order_number',
        'total_ongkir',
        'total_bayar',
        'payment_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function metodePengiriman()
    {
        return $this->belongsTo(MetodePengiriman::class, 'metode_pengiriman_id');
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class, 'metode_pembayaran_id');
    }

    public function deliveryStatus()
    {
        return $this->belongsTo(DeliveryStatus::class, 'delivery_status_id');
    }
}
