@props(['product'])

@php
    $gambar = data_get($product, 'gambar', data_get($product, 'image', null));
    $defaultPath = 'assets/photoProduct/default_batik.svg';
    if (!$gambar || !file_exists(public_path($gambar))) {
        $gambar = $defaultPath;
    }
    $terjual = data_get($product, 'terjual', 0);
    $stok = data_get($product, 'stok', 0);
    $name = data_get($product, 'nama_produk', data_get($product, 'name', 'Produk'));
    $harga = data_get($product, 'harga', 0);
    $id = data_get($product, 'id');
    $diskonPersen = data_get($product, 'diskon_persen', 0);
    $hargaDiskon = data_get($product, 'harga_diskon', $harga);
    $productJson = json_encode([
        'id' => $id,
        'nama_produk' => $name,
        'gambar' => $gambar,
        'terjual' => $terjual,
        'stok' => $stok,
        'harga' => $harga,
        'diskon_persen' => $diskonPersen,
        'harga_diskon' => $hargaDiskon,
        'material' => data_get($product, 'material'),
        'proses' => data_get($product, 'proses'),
        'sku' => data_get($product, 'sku'),
        'kategori' => data_get($product, 'kategori'),
        'tags' => data_get($product, 'tags'),
        'deskripsi' => data_get($product, 'deskripsi'),
        'jenis_lengan' => data_get($product, 'jenis_lengan'),
        'ukuran' => data_get($product, 'ukuran'),
    ]);
@endphp

<div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-200 group product-card-item" 
     data-product='{{ $productJson }}'>
    {{-- Product Image --}}
    <div class="relative overflow-hidden bg-gradient-to-br from-amber-50 to-orange-50">
        <img src="{{ asset($gambar) }}" 
             alt="{{ $name }}" 
             class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-300">
        
        {{-- Stock Badge --}}
        <div class="absolute top-3 right-3">
            @if($stok > 10)
                <span class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow-md">
                    Stok: {{ $stok }}
                </span>
            @elseif($stok > 0)
                <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-semibold rounded-full shadow-md">
                    Stok: {{ $stok }}
                </span>
            @else
                <span class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-md">
                    Habis
                </span>
            @endif
        </div>
    </div>

    {{-- Product Info --}}
    <div class="p-4">
        <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 min-h-[3rem]">{{ $name }}</h3>
        
        <div class="mb-3">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xl font-bold text-amber-700">
                    Rp {{ number_format($hargaDiskon, 0, ',', '.') }}
                </span>
                @if($diskonPersen > 0)
                    <span class="text-xs font-semibold px-2 py-1 bg-green-500 text-white rounded">
                        -{{ $diskonPersen }}%
                    </span>
                @endif
            </div>
            <span class="text-sm text-gray-500 flex items-center gap-1">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                </svg>
                {{ $terjual }} Terjual
            </span>
        </div>

        {{-- Action Buttons --}}
        <div class="flex gap-2">
            <button class="edit-button flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2" 
                    data-id="{{ $id }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </button>
            <button class="delete-button bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg" 
                    data-id="{{ $id }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>
</div>