@extends('customer.layouts.mainLayout')

@section('container')
    <div class="favorites-page-container">
        <h1>Produk Favorit</h1>
        
        <div class="favorites-list-wrapper">
            @if (count($favoriteItems) > 0)
                <div class="favorites-card-list">
                    @foreach ($favoriteItems as $item)
                        @include('customer.components.favorite-item', ['item' => $item])
                    @endforeach
                </div>
            @else
                <p class="empty-list">Kamu belum menambahkan produk ke daftar favorit.</p>
            @endif
        </div>
    </div>
@endsection