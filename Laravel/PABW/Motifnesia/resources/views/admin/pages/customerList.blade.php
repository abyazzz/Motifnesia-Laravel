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

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <main class="data-table-wrapper">
            {{-- Header/Kolom Tabel --}}
            <div class="table-header">
                <div class="header-col username-col">Username</div>
                <div class="header-col fullname-col">Nama Lengkap</div>
                <div class="header-col email-col">Email</div>
                <div class="header-col products-col">Total Produk</div>
                <div class="header-col actions-col">Aksi</div>
            </div>

            {{-- Loop Data Pelanggan --}}
            <div class="customer-cards-list">
                @forelse($customers as $index => $customer)
                    @include('admin.components.customerCard', [
                        'customer' => $customer,
                        // Cek index ganjil/genap (0 adalah genap, 1 adalah ganjil, dst)
                        'isOdd' => ($index % 2 != 0)
                    ])
                @empty
                    <div class="no-customers">
                        <p>Belum ada pelanggan yang melakukan pembelian.</p>
                    </div>
                @endforelse
            </div>
        </main>
    </div>
@endsection