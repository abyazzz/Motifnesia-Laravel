<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Produk;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Produk::count() > 0) {
            return;
        }

        Produk::create([
            'gambar' => 'photoProduct/foto1.jpg',
            'nama_produk' => 'Batik Kawung Modern',
            'harga' => 150000.00,
            'material' => 'Katun Prima',
            'proses' => 'Cap',
            'sku' => 'BTK-KWG-001',
            'tags' => 'batik,kawung,modern',
            'stok' => 50,
            'kategori' => 'Kemeja',
            'jenis_lengan' => 'Pendek',
            'terjual' => '25',
            'deskripsi' => 'Batik motif kawung dengan desain modern, cocok untuk acara formal maupun santai.',
            'diskon_persen' => 0,
            'harga_diskon' => null,
            'ukuran' => 'S,M,L,XL',
        ]);

        Produk::create([
            'gambar' => 'photoProduct/foto2.jpg',
            'nama_produk' => 'Batik Parang Premium',
            'harga' => 200000.00,
            'material' => 'Sutra',
            'proses' => 'Tulis',
            'sku' => 'BTK-PRG-002',
            'tags' => 'batik,parang,premium',
            'stok' => 30,
            'kategori' => 'Kemeja',
            'jenis_lengan' => 'Panjang',
            'terjual' => '15',
            'deskripsi' => 'Batik motif parang dengan kualitas premium, dibuat dengan teknik tulis tangan.',
            'diskon_persen' => 10,
            'harga_diskon' => 180000.00,
            'ukuran' => 'M,L,XL',
        ]);

        Produk::create([
            'gambar' => 'images/default_batik_large.png',
            'nama_produk' => 'Batik Mega Mendung',
            'harga' => 175000.00,
            'material' => 'Katun Dobby',
            'proses' => 'Kombinasi',
            'sku' => 'BTK-MGM-003',
            'tags' => 'batik,mega mendung,cirebon',
            'stok' => 40,
            'kategori' => 'Kemeja',
            'jenis_lengan' => 'Pendek',
            'terjual' => '30',
            'deskripsi' => 'Batik khas Cirebon dengan motif mega mendung yang ikonik.',
            'diskon_persen' => 0,
            'harga_diskon' => null,
            'ukuran' => 'S,M,L,XL',
        ]);

        Produk::create([
            'gambar' => 'photoProduct/foto1.jpg',
            'nama_produk' => 'Batik Truntum Klasik',
            'harga' => 165000.00,
            'material' => 'Katun',
            'proses' => 'Cap',
            'sku' => 'BTK-TRT-004',
            'tags' => 'batik,truntum,klasik',
            'stok' => 60,
            'kategori' => 'Kemeja',
            'jenis_lengan' => 'Panjang',
            'terjual' => '20',
            'deskripsi' => 'Batik motif truntum dengan sentuhan klasik yang elegan.',
            'diskon_persen' => 5,
            'harga_diskon' => 156750.00,
            'ukuran' => 'M,L,XL',
        ]);
    }
}
