@extends('customer.layouts.mainLayout')

@section('container')
    <div class="product-detail-container">
        {{-- WRAPPER UNTUK KONTEN UTAMA DETAIL PRODUK --}}
        <div class="product-detail"> 
            
            {{-- BAGIAN KIRI: GAMBAR PRODUK --}}
            <div class="product-image-section">
                <img src="{{ asset('images/' . $product['gambar']) }}" alt="{{ $product['nama'] }}" class="product-image">
            </div>

            {{-- BAGIAN KANAN ATAS: INFORMASI HARGA & DETAIL --}}
            <div class="product-info">
                <div class="product-info-left">
                    <h2>{{ $product['nama'] }}</h2>
                    {{-- Harga di Controller adalah 150000, tapi di foto Rp701.522. Kita pakai data Controller --}}
                    <p class="product-price">Rp{{ number_format($product['harga'], 0, ',', '.') }}</p> 

                    <p><strong>Material:</strong>Sutra</p>
                    <p><strong>Proses:</strong> print</p>

                    <p style="margin-top: 1rem;"><strong>SKU:</strong> SKU0001</p>
                    <p><strong>Kategori:</strong> wanita</p>
                    <p><strong>Tags:</strong>batik, pria</p>

                    <p style="margin-top: 2rem;"><strong>Ukuran:</strong></p>
                    <div class="size-options">
                        <label><input type="radio" name="size" value="SS"> SS</label>
                        <label><input type="radio" name="size" value="S"> S</label>
                        <label><input type="radio" name="size" value="M"> M</label>
                        <label><input type="radio" name="size" value="L"> L</label>
                        <label><input type="radio" name="size" value="XL"> XL</label>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="action-buttons">
                        <button type="button" class="btn-cart" id="btnAddToCart">Tambah ke Keranjang</button>
                        @push('scripts')
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('btnAddToCart').addEventListener('click', function() {
                                const ukuran = document.querySelector('input[name="size"]:checked');
                                if (!ukuran) {
                                    alert('Pilih ukuran terlebih dahulu!');
                                    return;
                                }
                                fetch("{{ route('customer.cart.add') }}", {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        product_id: {{ $product['id'] }},
                                        ukuran: ukuran.value
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        alert(data.message);
                                        window.location.href = "{{ route('customer.cart.index') }}";
                                    } else {
                                        alert(data.message || 'Gagal menambah ke keranjang!');
                                    }
                                })
                                .catch(() => alert('Terjadi kesalahan.'));
                            });
                        });
                        </script>
                        @endpush
                        <button type="button" class="btn-wishlist"><i class="fa-regular fa-heart"></i></button>
                    </div>
                </div>

                {{-- BAGIAN KANAN BAWAH: DESKRIPSI (di foto posisinya di kanan atas) --}}
                <div class="product-description-section">
                    <h2>Deskripsi</h2>
                    <p>{{ $product['deskripsi'] }}</p>
                    {{-- Tambahkan deskripsi sesuai foto --}}
                    <p>Batik Mega Mendung khas daerah Indonesia</p>
                </div>
            </div>
            
        </div>
        
        {{-- Tombol kembali yang kamu hapus sebelumnya, gue kembalikan biar navigasi enak --}}
        

    </div>
@endsection