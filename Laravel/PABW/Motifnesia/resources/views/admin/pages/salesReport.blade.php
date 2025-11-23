@extends('admin.layouts.mainLayout')

@section('title', 'Laporan Penjualan')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/salesReport.css') }}">

    <div class="sales-report-container">
        <header class="report-header">
            <h1 class="header-title">Laporan Penjualan</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        <main class="report-content">
            {{-- Bagian Grafik Penjualan Bulanan --}}
            <h2 class="section-title">Penjualan Bulanan</h2>
            <div class="chart-wrapper">
                <div class="bar-chart-container">
                    @foreach($monthlySales as $sale)
                        <div class="chart-bar-column">
                            {{-- Gunakan data 'value' dan 'color' untuk styling dinamis --}}
                            <div class="chart-bar bar-{{ $sale['color'] }}" style="height: {{ $sale['value'] }}%;"></div>
                            <span class="chart-label">{{ $sale['month'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bagian Tabel Produk Terlaris --}}
            <h2 class="section-title">Produk Terlaris</h2>
            <div class="data-table-wrapper best-seller-table">
                {{-- Header Tabel --}}
                <div class="table-header">
                    <div class="header-col seller-col-name">Nama Produk</div>
                    <div class="header-col seller-col-unit">Unit Terjual</div>
                    <div class="header-col seller-col-revenue">Pendapatan</div>
                </div>

                {{-- Loop Data Produk Terlaris --}}
                <div class="best-seller-rows-list">
                    @foreach($bestSellers as $item)
                        @include('admin.components.bestSellerRow', ['item' => $item])
                    @endforeach
                </div>
            </div>
        </main>
    </div>
@endsection