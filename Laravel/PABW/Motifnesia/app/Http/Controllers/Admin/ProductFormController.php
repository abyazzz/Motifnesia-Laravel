<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductFormController extends Controller
{
    /**
     * Menampilkan halaman form Tambah Produk.
     */
    public function create()
    {
        // Data dummy untuk default form (kosong karena ini form 'Tambah')
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
            'description' => '', // Tambahan Deskripsi
            'image' => asset('/images/default_batik_large.png'), // Placeholder image
        ];

        return view('admin.pages.addProduct', [
            'product' => $product,
            'formTitle' => 'Tambah Produk',
            'activePage' => 'products-create'
        ]);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'nullable|numeric',
            'material' => 'nullable|string|max:100',
            'process' => 'nullable|string|max:100',
            'sku' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ukuran' => 'nullable|string|max:10',
            'jenis_lengan' => 'nullable|string|max:50',
            'stock' => 'nullable|integer',
            'image' => 'nullable|image|max:5120',
        ]);

        // Handle image upload: save directly to public/assets/photoProduct
        $gambarPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('assets/photoProduct');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Move uploaded file to public/assets/photoProduct
            $file->move($destinationPath, $filename);

            // Save web-accessible path (use with asset())
            $gambarPath = 'assets/photoProduct/' . $filename;
        }

        $produk = Produk::create([
            'gambar' => $gambarPath,
            'nama_produk' => $data['name'],
            'harga' => $data['price'] ?? null,
            'material' => $data['material'] ?? null,
            'proses' => $data['process'] ?? null,
            'sku' => $data['sku'] ?? null,
            'tags' => $data['tags'] ?? null,
            'stok' => $data['stock'] ?? 0,
            'kategori' => $data['category'] ?? null,
            'jenis_lengan' => $data['jenis_lengan'] ?? null,
            'ukuran' => $data['ukuran'] ?? null,
            'terjual' => null,
            'deskripsi' => $data['description'] ?? null,
            'diskon_persen' => 0,
            'harga_diskon' => null,
        ]);

        return redirect()->route('admin.daftar-produk')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Update existing product
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $data = $request->validate([
            'name' => 'nullable|string|max:100',
            'price' => 'nullable|numeric',
            'material' => 'nullable|string|max:100',
            'process' => 'nullable|string|max:100',
            'sku' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
            'tags' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ukuran' => 'nullable|string|max:10',
            'jenis_lengan' => 'nullable|string|max:50',
            'stock' => 'nullable|integer',
            'image' => 'nullable|image|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('assets/photoProduct');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $produk->gambar = 'assets/photoProduct/' . $filename;
        }

        // Update fields if provided
        if (isset($data['name'])) $produk->nama_produk = $data['name'];
        if (isset($data['price'])) $produk->harga = $data['price'];
        if (isset($data['material'])) $produk->material = $data['material'];
        if (isset($data['process'])) $produk->proses = $data['process'];
        if (isset($data['sku'])) $produk->sku = $data['sku'];
        if (isset($data['tags'])) $produk->tags = $data['tags'];
        if (isset($data['stock'])) $produk->stok = $data['stock'];
        if (isset($data['category'])) $produk->kategori = $data['category'];
        if (isset($data['jenis_lengan'])) $produk->jenis_lengan = $data['jenis_lengan'];
        if (isset($data['ukuran'])) $produk->ukuran = $data['ukuran'];
        if (isset($data['description'])) $produk->deskripsi = $data['description'];

        $produk->save();

        return response()->json(['success' => true, 'message' => 'Produk berhasil diperbarui']);
    }

    /**
     * Delete product
     */
    public function destroy(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        // Optionally remove image file
        if ($produk->gambar) {
            $path = public_path($produk->gambar);
            if (file_exists($path)) {
                @unlink($path);
            }
        }
        $produk->delete();

        return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus']);
    }
}