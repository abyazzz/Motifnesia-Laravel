@php
  $img = '';
  if (!empty($product['gambar'])) {
    if (strpos($product['gambar'], 'assets/') === 0) {
      $img = asset($product['gambar']);
    } else {
      $img = asset('images/' . $product['gambar']);
    }
  } else {
    $img = asset('images/1763996124_IMG_1516.JPG');
  }
  
  $rating = $product['rating'] ?? 5.0;
  $fullStars = floor($rating);
  $halfStar = ($rating - $fullStars) >= 0.5;
@endphp

<a href="{{ route('customer.product.detail', ['id' => $product['id']]) }}" 
   class="block group hover:no-underline">
  <div class="bg-white rounded-lg overflow-hidden transition-all duration-200" style="min-height: 230px; min-width: 175px; box-shadow: 0 0 10px 0 rgba(0,0,0,0.1);" onmouseover="this.style.boxShadow='0 0 15px 0 rgba(0,0,0,0.15)'" onmouseout="this.style.boxShadow='0 0 10px 0 rgba(0,0,0,0.1)'">
    {{-- Product Image --}}
    <div class="relative overflow-hidden aspect-square bg-gray-100">
      <img src="{{ $img }}" 
           alt="{{ $product['nama'] }}"
           class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
      
      {{-- Badge Batik (sesuai foto) --}}
      <div class="absolute top-2 left-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded text-xs font-medium text-gray-700 flex items-center gap-1">
        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
        </svg>
        Batik
      </div>
    </div>
    
    {{-- Product Info --}}
    <div class="p-3">
      <h3 class="text-sm font-semibold text-gray-800 mb-1 line-clamp-2 group-hover:text-orange-600 transition-colors min-h-[40px]">
        {{ $product['nama'] }}
      </h3>
      
      @php
        $hargaDiskon = $product['harga_diskon'] ?? $product['harga'];
        $diskonPersen = $product['diskon_persen'] ?? 0;
      @endphp
      
      <div class="flex items-center gap-2 mb-2">
        <p class="text-base font-bold text-orange-600">
          Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
        </p>
        @if($diskonPersen > 0)
          <span class="text-xs font-semibold px-2 py-0.5 bg-green-500 text-white rounded">
            -{{ $diskonPersen }}%
          </span>
        @endif
      </div>
      
      {{-- Rating & Terjual --}}
      <div class="flex items-center justify-between">
        {{-- Rating --}}
        <div class="flex items-center gap-1.5">
          <div class="flex gap-0.5">
            @for($i = 1; $i <= 5; $i++)
              @if($i <= $fullStars)
                <span class="text-yellow-400 text-xs">★</span>
              @elseif($i == $fullStars + 1 && $halfStar)
                <span class="text-yellow-400 text-xs">★</span>
              @else
                <span class="text-gray-300 text-xs">★</span>
              @endif
            @endfor
          </div>
          <span class="text-xs font-medium text-gray-600">{{ number_format($rating, 1) }}</span>
        </div>

        {{-- Terjual --}}
        <div class="text-xs text-gray-500">
          Terjual {{ $product['terjual'] ?? 0 }}
        </div>
      </div>
    </div>
  </div>
</a>
