@extends('admin.layouts.mainLayout')

@section('title', 'Kelola Retur')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/returnManagement.css') }}">

    <div class="return-management-container">
        <header class="management-header">
            <h1 class="header-title">Kelola Retur</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        <main class="data-table-wrapper">
            {{-- Header/Kolom Tabel --}}
            <div class="table-header">
                <div class="header-col col-id">Id Pelanggan</div>
                <div class="header-col col-name">Nama Pelanggan</div>
                <div class="header-col col-product">Nama Produk</div>
                <div class="header-col col-reason">Alasan Pengambilan</div>
                <div class="header-col col-action">Aksi</div>
            </div>

            {{-- Loop Data Retur --}}
            <div class="return-rows-list">
                @foreach($returns as $index => $returnItem)
                    @include('admin.components.returnRow', [
                        'returnItem' => $returnItem,
                        // Cek index ganjil/genap
                        'isOdd' => ($index % 2 != 0)
                    ])
                @endforeach
            </div>
        </main>
        
        {{-- Pagination (Sesuai Screenshot) --}}
        <footer class="pagination-footer">
            <button class="pagination-btn disabled"><</button>
            <span class="page-number">1</span>
            <button class="pagination-btn">></button>
        </footer>
    </div>
@endsection