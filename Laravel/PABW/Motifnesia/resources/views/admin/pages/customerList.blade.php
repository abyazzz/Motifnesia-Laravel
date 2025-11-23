@extends('admin.layouts.mainLayout')

@section('title', 'Daftar Pelanggan')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/customerList.css') }}">

    <div class="customer-list-container">
        <header class="list-header">
            <h1 class="header-title">Daftar Pelanggan</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        <main class="data-table-wrapper">
            {{-- Header/Kolom Tabel --}}
            <div class="table-header">
                <div class="header-col name-col">Nama</div>
                <div class="header-col email-col">Email</div>
            </div>

            {{-- Loop Data Pelanggan --}}
            <div class="customer-cards-list">
                @foreach($customers as $index => $customer)
                    @include('admin.components.customerCard', [
                        'customer' => $customer,
                        // Cek index ganjil/genap (0 adalah genap, 1 adalah ganjil, dst)
                        'isOdd' => ($index % 2 != 0)
                    ])
                @endforeach
            </div>
        </main>
    </div>
@endsection