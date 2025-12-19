@extends('customer.layouts.mainLayout')

@section('container')

<div class="min-h-screen pt-20 px-8" style="background-color: #f5f5f5;">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-6 pt-3">Favorite</h2>

        {{-- Success/Error Messages --}}
        @if(session('success'))
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        @forelse($favorites as $favorite)
            <div class="bg-white rounded-lg p-4 mb-3" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="flex items-center gap-4">

                    <!-- Image -->
                    <img src="{{ asset($favorite->produk->gambar) }}" 
                         alt="{{ $favorite->produk->nama_produk }}"
                         class="w-20 h-20 object-cover rounded-lg">

                    <!-- Product Info -->
                    <div class="flex-1">
                        <div class="font-semibold text-lg">
                            {{ $favorite->produk->nama_produk }}
                        </div>
                        @php
                            $hargaDiskon = $favorite->produk->harga_diskon ?? $favorite->produk->harga;
                            $diskonPersen = $favorite->produk->diskon_persen ?? 0;
                        @endphp
                        <div class="flex items-center gap-2 mt-1">
                            <div class="text-gray-600 text-sm">
                                Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                            </div>
                            @if($diskonPersen > 0)
                                <span class="text-xs font-semibold px-2 py-0.5 bg-green-500 text-white rounded">
                                    -{{ $diskonPersen }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        {{-- Add to Cart Button --}}
                        <a href="{{ route('customer.favorites.addToCart', $favorite->id) }}" 
                           class="px-6 py-2 rounded-lg font-semibold text-white transition-colors" 
                           style="background-color: #8B4513;">
                            Tambah Keranjang
                        </a>
                        
                        {{-- Delete Button --}}
                        <form action="{{ route('customer.favorites.destroy', $favorite->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Hapus produk dari favorite?')"
                                    class="text-red-500 hover:text-red-700 p-2">
                                <i class="fa fa-trash text-xl"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg p-8 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <p class="text-gray-600 mb-4">Belum ada produk favorite</p>
                <a href="{{ route('customer.home') }}" class="inline-block px-6 py-2 bg-orange-700 text-white rounded-lg hover:bg-orange-800">
                    Mulai Belanja
                </a>
            </div>
        @endforelse

        {{-- Button Perbarui Favorite --}}
        @if($favorites->count() > 0)
            <div class="text-center mt-6">
                <a href="{{ route('customer.home') }}" 
                   class="inline-block px-8 py-3 rounded-lg font-semibold text-white transition-colors"
                   style="background-color: #8B4513;">
                    ❤️ Perbarui Favorite
                </a>
            </div>
        @endif
    </div>
</div>
@endsection