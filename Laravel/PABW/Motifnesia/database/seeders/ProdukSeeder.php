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
            'gambar' => 'images/default_batik_large.png',
            'nama_produk' => 'Batik Contoh Sample',
            'harga' => 199000.00,
            'material' => 'Katun',
            'proses' => 'Cap',
            'sku' => 'BTK-SAMPLE-001',
            'tags' => 'batik,pria,kemeja',
            'stok' => 99,
            'kategori' => 'Kemeja',
            'jenis_lengan' => 'Panjang',
            'terjual' => '99',
            'deskripsi' => 'Contoh produk batik untuk testing tampilan.',
            'diskon_persen' => 0,
            'harga_diskon' => null,
        ]);
    }
}
