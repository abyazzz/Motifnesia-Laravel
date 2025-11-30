<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $fillable = [
        'gambar',
        'nama_produk',
        'harga',
        'material',
        'proses',
        'sku',
        'tags',
        'stok',
        'ukuran',
        'kategori',
        'jenis_lengan',
        'terjual',
        'deskripsi',
        'diskon_persen',
        'harga_diskon',
    ];

    
}
