@props(['product'])

@php
    // Ensure fields exist when $product is Eloquent model or array
    $gambar = data_get($product, 'gambar', data_get($product, 'image', null));
    // If gambar is empty or file doesn't exist, fallback to default in public/assets/photoProduct
    $defaultPath = 'assets/photoProduct/default_batik.svg';
    if (!$gambar || !file_exists(public_path($gambar))) {
        $gambar = $defaultPath;
    }
    $terjual = data_get($product, 'terjual', 0);
    $stok = data_get($product, 'stok', 0);
    $name = data_get($product, 'nama_produk', data_get($product, 'name', 'Produk'));
    $id = data_get($product, 'id');
    // Prepare JSON-safe product data for modal
    $productJson = json_encode([
        'id' => $id,
        'nama_produk' => $name,
        'gambar' => $gambar,
        'terjual' => $terjual,
        'stok' => $stok,
        'harga' => data_get($product, 'harga'),
        'material' => data_get($product, 'material'),
        'proses' => data_get($product, 'proses'),
        'sku' => data_get($product, 'sku'),
        'tags' => data_get($product, 'tags'),
        'deskripsi' => data_get($product, 'deskripsi'),
        'jenis_lengan' => data_get($product, 'jenis_lengan'),
        'ukuran' => data_get($product, 'ukuran'),
    ]);
@endphp

<div class="product-management-card" data-product='{{ $productJson }}'>
    <div class="card-image-wrapper">
        <img src="{{ asset($gambar) }}" alt="{{ $name }}" class="product-image">
    </div>
    <div class="card-info">
        <p class="product-stats">Terjual: {{ $terjual ?? 0 }}</p>
        <p class="product-stats">Stok : {{ $stok ?? 0 }}</p>
    </div>
    <div class="card-action">
        <button class="edit-button" data-id="{{ $id }}">Edit Produk</button>
        <button class="delete-button" data-id="{{ $id }}">Hapus Produk</button>
    </div>
</div>