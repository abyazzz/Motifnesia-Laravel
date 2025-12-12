@extends('admin.layouts.mainLayout')

@section('title', 'Ulasan Produk')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/productReviews.css') }}">

    {{-- Notifikasi Toast untuk produk tanpa ulasan --}}
    <div id="toast-notification" class="toast-notification">
        <span id="toast-message"></span>
    </div>

    <div class="product-reviews-container">
        <header class="reviews-header">
            <h1 class="header-title">Ulasan Produk</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari produk..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        {{-- Filter Dropdown --}}
        <div class="filter-section">
            <label for="filter-select">Filter:</label>
            <select id="filter-select" class="filter-dropdown" onchange="applyFilter(this.value)">
                <option value="all" {{ $currentFilter == 'all' ? 'selected' : '' }}>Semua Produk</option>
                <option value="highest" {{ $currentFilter == 'highest' ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="lowest" {{ $currentFilter == 'lowest' ? 'selected' : '' }}>Rating Terendah</option>
            </select>
        </div>

        <main class="data-table-wrapper">
            {{-- Header/Kolom Tabel --}}
            <div class="table-header">
                <div class="header-col product-image-col">Foto</div>
                <div class="header-col product-name-col">Nama Produk</div>
                <div class="header-col rating-col">Rating Rata-rata</div>
                <div class="header-col total-reviews-col">Total Ulasan</div>
                <div class="header-col actions-col">Aksi</div>
            </div>

            {{-- Loop Data Produk --}}
            <div class="product-reviews-list">
                @forelse($products as $index => $product)
                    @include('admin.components.productReviewCard', [
                        'product' => $product,
                        'isOdd' => ($index % 2 != 0)
                    ])
                @empty
                    <div class="no-reviews">
                        <p>Belum ada produk di sistem.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>

    {{-- JavaScript untuk Filter dan Toast Notification --}}
    <script>
        function applyFilter(filterValue) {
            window.location.href = "{{ route('admin.reviews.index') }}?filter=" + filterValue;
        }

        function showToast(message) {
            const toast = document.getElementById('toast-notification');
            const toastMessage = document.getElementById('toast-message');
            
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 5000);
        }
    </script>
@endsection