@php
  $img = '';
  if (!empty($product['gambar'])) {
    // if path already points to assets/ (uploaded via admin), use it directly
    if (strpos($product['gambar'], 'assets/') === 0) {
      $img = asset($product['gambar']);
    } else {
      // fallback to legacy `images/` folder
      $img = asset('images/' . $product['gambar']);
    }
  } else {
    $img = asset('images/1763996124_IMG_1516.JPG');
  }
@endphp

<a href="{{ route('product.detail', ['id' => $product['id']]) }}" class="product-link">
  <div class="product-card">
    <img src="{{ $img }}" alt="{{ $product['nama'] }}">
    <h3>{{ $product['nama'] }}</h3>
    <p>Harga: Rp{{ number_format($product['harga'], 0, ',', '.') }}</p>
  </div>
</a>
