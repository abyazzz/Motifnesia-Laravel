<a href="{{ route('product.detail', ['id' => $product['id']]) }}" class="product-link">
    <div class="product-card">
      <img src="{{ asset('images/' . $product['gambar']) }}" alt="{{ $product['nama'] }}">   
        <h3>{{ $product['nama'] }}</h3>
        <p>Harga: Rp{{ number_format($product['harga'], 0, ',', '.') }}</p>
    </div>
</a>
