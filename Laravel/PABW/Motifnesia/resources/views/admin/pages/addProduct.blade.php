@extends('admin.layouts.mainLayout')

@section('title', $formTitle)

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/addProduct.css') }}">

    <div class="add-product-container">
        <header class="form-header">
            <h1 class="header-title">{{ $formTitle }}</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        <main class="product-form-wrapper">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
                @csrf
                
                {{-- Kiri: Kolom Input Form --}}
                <div class="form-column form-left">
                    
                    {{-- Nama Produk --}}
                    <label for="name">Nama Produk</label>
                    <input type="text" id="name" name="name" value="{{ $product['name'] }}" required>

                    {{-- Harga --}}
                    <label for="price">Harga</label>
                    <input type="number" id="price" name="price" value="{{ $product['price'] }}" step="0.01" min="0">

                    {{-- Material --}}
                    <label for="material">Material</label>
                    <select id="material" name="material">
                        <option value="">-- Pilih Material --</option>
                        <option value="Katun" {{ isset($product['material']) && $product['material'] == 'Katun' ? 'selected' : '' }}>Katun</option>
                        <option value="Sutra" {{ isset($product['material']) && $product['material'] == 'Sutra' ? 'selected' : '' }}>Sutra</option>
                    </select>

                    {{-- Proses --}}
                    <label for="process">Proses</label>
                    <select id="process" name="process">
                        <option value="">-- Pilih Proses --</option>
                        <option value="Press" {{ isset($product['process']) && $product['process'] == 'Press' ? 'selected' : '' }}>Press</option>
                        <option value="Tulis" {{ isset($product['process']) && $product['process'] == 'Tulis' ? 'selected' : '' }}>Tulis</option>
                    </select>
                    
                    {{-- SKU --}}
                    <label for="sku">SKU</label>
                    <input type="text" id="sku" name="sku" value="{{ $product['sku'] }}">

                    {{-- Kategori --}}
                    <label for="category">Kategori</label>
                    <select id="category" name="category">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Pria" {{ isset($product['category']) && $product['category'] == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ isset($product['category']) && $product['category'] == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        <option value="Anak-anak" {{ isset($product['category']) && $product['category'] == 'Anak-anak' ? 'selected' : '' }}>Anak-anak</option>
                    </select>
                    
                    {{-- Tags --}}
                    <label for="tags">Tags</label>
                    <input type="text" id="tags" name="tags" value="{{ $product['tags'] }}">

                    {{-- Deskripsi (Tambahan) --}}
                    <label for="description">Deskripsi Produk</label>
                    <textarea id="description" name="description" rows="3" style="width:100%;">{{ $product['description'] }}</textarea>

                    {{-- Ukuran --}}
                    <label for="ukuran">Ukuran</label>
                    <select id="ukuran" name="ukuran">
                        <option value="">-- Pilih Ukuran --</option>
                        <option value="S" {{ isset($product['ukuran']) && $product['ukuran'] == 'S' ? 'selected' : '' }}>S</option>
                        <option value="M" {{ isset($product['ukuran']) && $product['ukuran'] == 'M' ? 'selected' : '' }}>M</option>
                        <option value="XL" {{ isset($product['ukuran']) && $product['ukuran'] == 'XL' ? 'selected' : '' }}>XL</option>
                    </select>

                    {{-- Jenis Lengan --}}
                    <label for="jenis_lengan">Jenis Lengan</label>
                    <select id="jenis_lengan" name="jenis_lengan">
                        <option value="">-- Pilih Jenis Lengan --</option>
                        <option value="Pendek" {{ isset($product['jenis_lengan']) && $product['jenis_lengan'] == 'Pendek' ? 'selected' : '' }}>Pendek</option>
                        <option value="Panjang" {{ isset($product['jenis_lengan']) && $product['jenis_lengan'] == 'Panjang' ? 'selected' : '' }}>Panjang</option>
                    </select>

                    {{-- Stok --}}
                    <label for="stock">Stok</label>
                    <input type="text" id="stock" name="stock" value="{{ $product['stock'] }}">
                    
                </div>
                
                {{-- Kanan: Foto dan Tombol Aksi --}}
                <div class="form-column form-right">
                    <div class="image-preview-box">
                        <img src="{{ $product['image'] }}" alt="Pratinjau Produk" class="product-image-preview" id="productPreview">

                        <input type="file" name="image" id="elect-image-button" class="elect-image-button" accept="image/*">
                    </div>

                    <div class="action-buttons-area">
                        {{-- Nama tombol di screenshot: Simpan Perubahan --}}
                        <button type="submit" class="save-button">Tambah Produk</button>
                    </div>
                </div>
            </form>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const fileInput = document.getElementById('elect-image-button');
            const preview = document.getElementById('productPreview');

            if (!fileInput) return;

            fileInput.addEventListener('change', function(e){
                const file = e.target.files && e.target.files[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                preview.src = url;
                preview.onload = function(){
                    URL.revokeObjectURL(url);
                }
            });
        });
    </script>
    
@endsection