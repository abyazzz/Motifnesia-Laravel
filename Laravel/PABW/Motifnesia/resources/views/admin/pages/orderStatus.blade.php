@extends('admin.layouts.mainLayout')

@section('title', 'Status Pembelian')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/orderStatus.css') }}">

    <div class="order-status-container">
        <header class="status-header">
            <h1 class="header-title">Status Pembelian</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        <main class="data-table-wrapper">
            {{-- Header (Sama) --}}
            <div class="table-header">
                <div class="header-col col-detail"></div>
                <div class="header-col col-address"></div>
                <div class="header-col col-status"></div>
            </div>

            {{-- Loop Data Status Pesanan --}}
            <div class="order-status-rows-list">
                @foreach($orders as $index => $order)
                    @include('admin.components.orderStatusRow', [
                        'order' => $order,
                        'isOdd' => ($index % 2 != 0)
                        // statusOptions SUDAH TIDAK DIKIRIM LAGI
                    ])
                @endforeach
            </div>
        </main>
    </div>
@endsection