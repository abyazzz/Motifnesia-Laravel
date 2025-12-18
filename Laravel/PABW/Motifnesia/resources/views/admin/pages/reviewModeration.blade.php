@extends('admin.layouts.mainLayout')

@section('title', 'Ulasan Produk')

@section('content')
    <div class="p-6">
        {{-- Toast Notification --}}
        <div id="toast-notification" class="fixed top-24 right-6 bg-amber-600 text-white px-6 py-4 rounded-lg shadow-xl transform translate-x-full transition-transform duration-300 z-50">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="toast-message" class="font-medium"></span>
            </div>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-4">
                    <div class="relative flex-1">
                        <input type="text" 
                               id="searchProduct"
                               placeholder="Cari produk..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <select id="filter-select" 
                            class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white min-w-[200px]"
                            onchange="applyFilter(this.value)">
                        <option value="all" {{ $currentFilter == 'all' ? 'selected' : '' }}>Semua Produk</option>
                        <option value="highest" {{ $currentFilter == 'highest' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="lowest" {{ $currentFilter == 'lowest' ? 'selected' : '' }}>Rating Terendah</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Reviews Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Table Header --}}
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
                <div class="grid grid-cols-12 gap-4 px-6 py-4 font-semibold text-gray-700">
                    <div class="col-span-1">Foto</div>
                    <div class="col-span-4">Nama Produk</div>
                    <div class="col-span-2 text-center">Rating</div>
                    <div class="col-span-2 text-center">Total Ulasan</div>
                    <div class="col-span-3 text-center">Aksi</div>
                </div>
            </div>

            {{-- Table Body --}}
            <div class="divide-y divide-gray-200">
                @forelse($products as $product)
                    @php
                        $avgRating = $product['average_rating'] ?? 0;
                        $totalReviews = $product['total_reviews'] ?? 0;
                        $gambar = $product['gambar'] ?? 'images/default.jpg';
                    @endphp
                    <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-gray-50 transition-colors duration-150 product-row" data-product='@json($product)'>
                        <div class="col-span-1 flex items-center">
                            <img src="{{ asset($gambar) }}" 
                                 alt="{{ $product['nama_produk'] }}" 
                                 class="w-16 h-16 object-cover rounded-lg border-2 border-gray-200">
                        </div>
                        <div class="col-span-4 flex items-center">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $product['nama_produk'] }}</h3>
                                <p class="text-sm text-gray-500">SKU: {{ $product['sku'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-span-2 flex items-center justify-center">
                            <div class="flex items-center gap-2">
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= floor($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="font-semibold text-gray-700">{{ number_format($avgRating, 1) }}</span>
                            </div>
                        </div>
                        <div class="col-span-2 flex items-center justify-center">
                            <span class="px-4 py-1.5 bg-amber-100 text-amber-800 rounded-full text-sm font-semibold">
                                {{ $totalReviews }} Ulasan
                            </span>
                        </div>
                        <div class="col-span-3 flex items-center justify-center gap-2">
                            <a href="{{ route('admin.reviews.show', $product['id']) }}" 
                               class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <div class="text-6xl mb-4">⭐</div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Produk</h3>
                        <p class="text-gray-600">Belum ada produk di sistem.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function applyFilter(filterValue) {
            window.location.href = "{{ route('admin.reviews.index') }}?filter=" + filterValue;
        }

        function showToast(message) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            toast.classList.remove('translate-x-full');
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchProduct');
            
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    document.querySelectorAll('.product-row').forEach(row => {
                        const product = JSON.parse(row.getAttribute('data-product'));
                        const namaProduk = (product.nama_produk || '').toLowerCase();
                        
                        if (namaProduk.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
@endsection