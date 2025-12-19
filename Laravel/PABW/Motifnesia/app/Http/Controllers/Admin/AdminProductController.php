<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
    // =========================
    // DAFTAR PRODUK (untuk admin.daftar-produk)
    // =========================
    public function index()
    {
        $products = Produk::orderBy('id', 'desc')->get();
        return view('admin.pages.daftarProduk', [
            'products' => $products,
            'activePage' => 'daftar-produk'
        ]);
    }

    // =========================
    // FORM CREATE PRODUK
    // =========================
    public function create()
    {
        // Data kosong untuk form tambah produk
        $product = [
            'name' => '',
            'price' => '',
            'material' => '',
            'process' => '',
            'sku' => '',
            'category' => '',
            'tags' => '',
            'ukuran' => '',
            'jenis_lengan' => '',
            'stock' => '',
            'description' => '',
            'image' => asset('images/default_batik_large.png'),
        ];

        return view('admin.pages.addProduct', [
            'product' => $product,
            'formTitle' => 'Tambah Produk',
            'activePage' => 'products-create'
        ]);
    }


    // =========================
    // STORE PRODUK BARU
    // =========================
    public function store(StoreProductRequest $request, ProductService $productService)
    {
        $data = $request->validated();

        // Upload gambar
        $gambarPath = null;
        if ($request->hasFile('image')) {
            $gambarPath = $productService->uploadProductImage($request->file('image'));
        }

        // Prepare product data dengan kalkulasi diskon
        $productData = $productService->prepareProductData($data, $gambarPath);

        // Simpan ke DB
        Produk::create($productData);

        return redirect()->route('admin.product.management.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // =========================
    // LIST / TABLE UNTUK EDIT & DELETE (Product Management)
    // =========================
    public function manage()
    {
        $products = Produk::orderBy('id', 'desc')->get();
        return view('admin.pages.productManagement', [
            'products' => $products,
            'activePage' => 'product-management'
        ]);
    }

    // =========================
    // UPDATE PRODUK (MODAL)
    // =========================
    public function update(UpdateProductRequest $request, $id, ProductService $productService)
    {
        $produk = Produk::findOrFail($id);
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($produk->gambar) {
                $productService->deleteProductImage($produk->gambar);
            }
            
            // Upload gambar baru
            $produk->gambar = $productService->uploadProductImage($request->file('image'));
        }

        // Update fields yang diinput
        if (isset($data['name'])) $produk->nama_produk = $data['name'];
        if (isset($data['description'])) $produk->deskripsi = $data['description'];
        if (isset($data['price'])) $produk->harga = $data['price'];
        if (isset($data['category'])) $produk->kategori = $data['category'];
        if (isset($data['stock'])) $produk->stok = $data['stock'];
        if (isset($data['material'])) $produk->material = $data['material'];
        if (isset($data['process'])) $produk->proses = $data['process'];
        if (isset($data['sku'])) $produk->sku = $data['sku'];
        if (isset($data['tags'])) $produk->tags = $data['tags'];
        if (isset($data['ukuran'])) $produk->ukuran = $data['ukuran'];
        if (isset($data['jenis_lengan'])) $produk->jenis_lengan = $data['jenis_lengan'];
        
        // Update diskon dan kalkulasi harga_diskon
        if (isset($data['diskon_persen'])) {
            $produk->diskon_persen = $data['diskon_persen'];
        }
        
        // Kalkulasi ulang harga_diskon menggunakan service
        $produk->harga_diskon = $productService->calculateDiscountedPrice(
            (float) $produk->harga, 
            (int) ($produk->diskon_persen ?? 0)
        );

        $produk->save();

        return response()->json([
            'success' => true, 
            'message' => 'Produk berhasil diupdate!'
        ]);
    }

    // =========================
    // HAPUS PRODUK
    // =========================
    public function destroy($id, ProductService $productService)
    {
        $produk = Produk::findOrFail($id);

        // Hapus gambar menggunakan service
        if ($produk->gambar) {
            $productService->deleteProductImage($produk->gambar);
        }

        $produk->delete();

        return response()->json([
            'success' => true, 
            'message' => 'Produk berhasil dihapus!'
        ]);
    }
}
