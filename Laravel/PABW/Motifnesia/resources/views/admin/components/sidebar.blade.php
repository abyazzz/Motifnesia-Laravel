@php
    $activePage = $activePage ?? 'customers'; 
    $navLinks = [
        'Manajemen Produk'      => ['route' => 'admin.product.management.index', 'page' => 'product-management', 'icon' => '📦'],
        'Ulasan Produk'         => ['route' => 'admin.reviews.index', 'page' => 'reviews', 'icon' => '⭐'],
        'Status Pengiriman'     => ['route' => 'admin.orders.status', 'page' => 'order-status', 'icon' => '🚚'],
        'Kelola Retur'          => ['route' => 'admin.returns.index', 'page' => 'returns', 'icon' => '↩️'],
        'Laporan Penjualan'     => ['route' => 'admin.reports.sales', 'page' => 'sales-report', 'icon' => '📊'],
        'Daftar Pelanggan'      => ['route' => 'admin.customers.index', 'page' => 'customers', 'icon' => '👥'],
        'Live Chat Support'     => ['route' => 'admin.chat.index', 'page' => 'live-chat', 'icon' => '💬'],
        'Notifikasi Sistem'     => ['route' => 'admin.notifications.index', 'page' => 'notification', 'icon' => '🔔'],
        'Kelola Konten Statis'  => ['route' => 'admin.konten.index', 'page' => 'konten-statis', 'icon' => '⚙️'],
    ];
@endphp

<div class="sidebar bg-gradient-to-b from-amber-50 to-orange-50 min-h-screen shadow-lg border-r border-amber-200">
    <div class="px-4 py-6">
        {{-- Logo dan Nama --}}
        <div class="flex items-center gap-3 mb-8 pb-4 border-b-2 border-amber-200">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-600 to-orange-700 rounded-lg flex items-center justify-center shadow-md">
                <span class="text-white font-bold text-xl">M</span>
            </div>
            <h3 class="text-xl font-bold bg-gradient-to-r from-amber-800 to-orange-800 bg-clip-text text-transparent">Motifnesia</h3>
        </div>

        {{-- Daftar Menu --}}
        <nav class="space-y-1 mb-[50px]">
            @foreach ($navLinks as $title => $link)
                @php
                    $isActive = $activePage === $link['page'];
                @endphp
                <a href="{{ $link['route'] == '#' ? '#' : route($link['route']) }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 group
                          {{ $isActive 
                             ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-md' 
                             : 'text-amber-900 hover:bg-amber-100 hover:shadow-sm' }}">
                    <span class="text-xl {{ $isActive ? 'scale-110' : 'group-hover:scale-110' }} transition-transform">
                        {{ $link['icon'] }}
                    </span>
                    <span class="font-medium text-sm">{{ $title }}</span>
                </a>
            @endforeach
        </nav>

        {{-- Tombol Logout --}}
        <div class="border-t border-amber-200 pt-4">
            <a href="{{ route('auth.logout') }}" 
               class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>