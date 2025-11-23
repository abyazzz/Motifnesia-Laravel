@extends('admin.layouts.mainLayout')

@section('title', 'Moderasi Ulasan')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/reviewModeration.css') }}">

    <div class="review-moderation-container">
        <header class="moderation-header">
            <h1 class="header-title">Moderasi Ulasan</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        <main class="data-table-wrapper">
            {{-- Header/Kolom Tabel --}}
            <div class="table-header">
                <div class="header-col review-name-col">Nama</div>
                <div class="header-col review-email-col">Email</div>
                <div class="header-col review-rating-col">Rating</div>
            </div>

            {{-- Loop Data Ulasan --}}
            <div class="review-cards-list">
                @foreach($reviews as $index => $review)
                    @include('admin.components.reviewCard', [
                        'review' => $review,
                        // Cek index ganjil/genap
                        'isOdd' => ($index % 2 != 0)
                    ])
                @endforeach
            </div>
        </main>
    </div>
@endsection