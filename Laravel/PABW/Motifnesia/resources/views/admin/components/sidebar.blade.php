@php
    // Definisikan variabel activePage di sini untuk dummy, 
    // dalam implementasi nyata, ini harus di-pass dari Controller/View
    // Contoh: 'customers', 'product-management', 'sales-report', dll.
    $activePage = $activePage ?? 'customers'; 

    // Daftar link dan nama route-nya
    $navLinks = [
        'Daftar Pelanggan'      => ['route' => 'admin.customers.index', 'page' => 'customers'],
        'Manajemen Produk'      => ['route' => 'admin.product.management.index', 'page' => 'product-management'],
        'Ulasan Produk'         => ['route' => 'admin.reviews.index', 'page' => 'reviews'],
        'Kelola Promo'          => ['route' => '#', 'page' => 'promo'], // Belum dibuat route-nya
        'Laporan Penjualan'     => ['route' => 'admin.reports.sales', 'page' => 'sales-report'],
        'Kelola Retur'          => ['route' => 'admin.returns.index', 'page' => 'returns'],
        'Status Pengiriman'     => ['route' => 'admin.orders.status', 'page' => 'order-status'],
        'Live Chat Support'     => ['route' => 'admin.chat.index', 'page' => 'live-chat'],
        'Tambah Produk'         => ['route' => 'admin.products.create', 'page' => 'products-create'],
        'Daftar Produk'         => ['route' => 'admin.daftar-produk', 'page' => 'daftar-produk'], // Dari route pertama
        'Notifikasi Sistem'     => ['route' => 'admin.notifications.index', 'page' => 'notification'],
        'Kelola Konten Statis'  => ['route' => 'admin.konten.index', 'page' => 'konten-statis'],
    ];
@endphp

<div style="width: 250px; background-color: #FFF4E0; height: 100vh; padding: 20px; display: flex; flex-direction: column; justify-content: space-between;">
    <div>
        {{-- Logo dan Nama --}}
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 30px;">
            {{-- Asumsi lu udah punya 'images/logo.png' --}}
            <img src="{{ asset('images/logo.png') }}" alt="Motifnesia Logo" style="width: 40px;"> 
            <h3 style="font-weight: bold; color: #4A4A4A;">Motifnesia</h3>
        </div>

        {{-- Daftar Menu --}}
        <ul style="list-style: none; padding: 0;">
            @foreach ($navLinks as $title => $link)
                @php
                    $isActive = $activePage === $link['page'];
                    $bgColor = $isActive ? '#A47C48' : 'transparent';
                    $textColor = $isActive ? 'white' : '#5A3E2B';
                @endphp
                <li>
                    <a href="{{ $link['route'] == '#' ? '#' : route($link['route']) }}" 
                       style="display: block; padding: 10px; background-color: {{ $bgColor }}; color: {{ $textColor }}; border-radius: 6px; margin-bottom: 5px; font-weight: 500; text-decoration: none;">
                        {{ $title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    {{-- Tombol Logout --}}
    <a href="{{ route('auth.logout') }}" 
       style="background-color: #A47C48; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px; text-decoration: none;">
        <span style="font-size: 16px;">
            &#128274; {{-- Unicode lock icon --}}
        </span>
        Logout
    </a>
</div>