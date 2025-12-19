<?php

namespace App\Services;

use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class ProductService
{
    /**
     * Upload product image
     * 
     * @param UploadedFile $file
     * @return string Path relatif gambar
     */
    public function uploadProductImage(UploadedFile $file): string
    {
        $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) 
                    . '-' . time() 
                    . '.' . $file->getClientOriginalExtension();
        
        $destinationPath = public_path('assets/photoProduct');
        
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }
        
        $file->move($destinationPath, $filename);
        
        return 'assets/photoProduct/' . $filename;
    }
    
    /**
     * Delete product image
     * 
     * @param string $imagePath
     * @return bool
     */
    public function deleteProductImage(string $imagePath): bool
    {
        $fullPath = public_path($imagePath);
        
        if (file_exists($fullPath)) {
            return @unlink($fullPath);
        }
        
        return false;
    }
    
    /**
     * Calculate discounted price
     * 
     * @param float $originalPrice
     * @param int $discountPercent
     * @return float
     */
    public function calculateDiscountedPrice(float $originalPrice, int $discountPercent): float
    {
        if ($discountPercent < 0 || $discountPercent > 100) {
            return $originalPrice;
        }
        
        return $originalPrice - ($originalPrice * ($discountPercent / 100));
    }
    
    /**
     * Prepare product data untuk create/update
     * 
     * @param array $data
     * @param string|null $imagePath
     * @return array
     */
    public function prepareProductData(array $data, ?string $imagePath = null): array
    {
        $hargaAsli = $data['price'];
        $diskonPersen = $data['diskon_persen'] ?? 0;
        $hargaDiskon = $this->calculateDiscountedPrice($hargaAsli, $diskonPersen);
        
        $productData = [
            'nama_produk'   => $data['name'],
            'deskripsi'     => $data['description'],
            'harga'         => $hargaAsli,
            'kategori'      => $data['category'],
            'stok'          => $data['stock'],
            'material'      => $data['material'],
            'proses'        => $data['process'],
            'sku'           => $data['sku'],
            'tags'          => $data['tags'],
            'ukuran'        => $data['ukuran'],
            'jenis_lengan'  => $data['jenis_lengan'],
            'diskon_persen' => $diskonPersen,
            'harga_diskon'  => $hargaDiskon,
        ];
        
        if ($imagePath) {
            $productData['gambar'] = $imagePath;
        }
        
        return $productData;
    }
    
    /**
     * Get product with calculated rating
     * 
     * @param Produk $product
     * @return array
     */
    public function formatProductWithRating(Produk $product): array
    {
        $avgRating = $product->reviews()->avg('rating') ?? 5.0;
        
        return [
            'id'            => $product->id,
            'nama'          => $product->nama_produk ?? '',
            'harga'         => $product->harga ?? 0,
            'harga_diskon'  => $product->harga_diskon ?? $product->harga ?? 0,
            'diskon_persen' => $product->diskon_persen ?? 0,
            'gambar'        => $product->gambar ?? '',
            'deskripsi'     => $product->deskripsi ?? '',
            'stok'          => $product->stok ?? 0,
            'terjual'       => $product->terjual ?? 0,
            'rating'        => round($avgRating, 1),
        ];
    }
}
