<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'required|string',
            'price'        => 'required|numeric',
            'category'     => 'required|string|max:100',
            'stock'        => 'required|integer',
            'material'     => 'required|string|max:100',
            'process'      => 'required|string|max:100',
            'sku'          => 'required|string|max:50',
            'tags'         => 'required|string|max:255',
            'ukuran'       => 'required|string|max:10',
            'jenis_lengan' => 'required|string|max:50',
            'image'        => 'required|image|mimes:png,jpg,jpeg,webp|max:5120',
        ]);

        // Upload gambar ke public/assets/photoProduct
        $gambarPath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('assets/photoProduct');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $gambarPath = 'assets/photoProduct/' . $filename;
        }

        // Simpan ke DB dengan kolom yang sesuai model Produk
        Produk::create([
            'nama_produk'   => $data['name'],
            'deskripsi'     => $data['description'],
            'harga'         => $data['price'],
            'kategori'      => $data['category'],
            'stok'          => $data['stock'],
            'material'      => $data['material'],
            'proses'        => $data['process'],
            'sku'           => $data['sku'],
            'tags'          => $data['tags'],
            'ukuran'        => $data['ukuran'],
            'jenis_lengan'  => $data['jenis_lengan'],
            'gambar'        => $gambarPath,
            'diskon_persen' => 0,
            'harga_diskon'  => null,
        ]);

        return redirect()->route('admin.daftar-produk');
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
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $data = $request->validate([
            'name'         => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'nullable|numeric',
            'category'     => 'nullable|string|max:100',
            'stock'        => 'nullable|integer',
            'material'     => 'nullable|string|max:100',
            'process'      => 'nullable|string|max:100',
            'sku'          => 'nullable|string|max:50',
            'tags'         => 'nullable|string|max:255',
            'ukuran'       => 'nullable|string|max:10',
            'jenis_lengan' => 'nullable|string|max:50',
            'image'        => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($produk->gambar && file_exists(public_path($produk->gambar))) {
                @unlink(public_path($produk->gambar));
            }

            $file = $request->file('image');
            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('assets/photoProduct');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $produk->gambar = 'assets/photoProduct/' . $filename;
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

        $produk->save();

        return response()->json(['success' => true, 'message' => 'Produk berhasil diupdate!']);
    }

    // =========================
    // HAPUS PRODUK
    // =========================
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus gambar di folder (gambar field berisi path relatif seperti 'assets/photoProduct/...')
        if ($produk->gambar && file_exists(public_path($produk->gambar))) {
            @unlink(public_path($produk->gambar));
        }

        $produk->delete();

        return response()->json(['success' => true, 'message' => 'Produk berhasil dihapus!']);
    }
}
