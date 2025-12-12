@extends('admin.layouts.mainLayout')

@section('title', 'Laporan Penjualan')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/salesReport.css') }}">

    <div class="sales-report-container">
        <header class="report-header">
            <h1 class="header-title">Laporan Penjualan</h1>
            
            {{-- Filter dan Export --}}
            <div class="header-actions">
                <select id="period-filter" class="period-dropdown" onchange="applyPeriodFilter(this.value)">
                    <option value="today" {{ $currentPeriod == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="7" {{ $currentPeriod == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30" {{ $currentPeriod == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="month" {{ $currentPeriod == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
                
                <a href="{{ route('admin.reports.export') }}?period={{ $currentPeriod }}" class="btn-export">
                    📥 Export Excel
                </a>
            </div>
        </header>

        {{-- Metrics Cards --}}
        <div class="metrics-container">
            <div class="metric-card">
                <div class="metric-icon">💰</div>
                <div class="metric-content">
                    <div class="metric-label">Total Revenue</div>
                    <div class="metric-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">🛒</div>
                <div class="metric-content">
                    <div class="metric-label">Total Orders</div>
                    <div class="metric-value">{{ $totalOrders }}</div>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">📦</div>
                <div class="metric-content">
                    <div class="metric-label">Products Sold</div>
                    <div class="metric-value">{{ $totalProductsSold }}</div>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon">📊</div>
                <div class="metric-content">
                    <div class="metric-label">Avg Order Value</div>
                    <div class="metric-value">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        {{-- Chart Section --}}
        <div class="chart-section">
            <h2 class="section-title">📈 Grafik Penjualan</h2>
            <div class="chart-wrapper">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="table-section">
            <h2 class="section-title">📋 Detail Transaksi</h2>
            <div class="data-table-wrapper">
                <div class="table-header">
                    <div class="header-col col-order">Order</div>
                    <div class="header-col col-date">Tanggal</div>
                    <div class="header-col col-customer">Customer</div>
                    <div class="header-col col-products">Produk</div>
                    <div class="header-col col-total">Total</div>
                    <div class="header-col col-payment">Payment</div>
                </div>
                
                <div class="table-rows-list">
                    @forelse($orders as $index => $order)
                        <div class="table-row {{ $index % 2 != 0 ? 'odd-row' : 'even-row' }}">
                            <div class="row-col col-order">#{{ $order->order_number }}</div>
                            <div class="row-col col-date">{{ $order->created_at->format('d M Y H:i') }}</div>
                            <div class="row-col col-customer">{{ $order->user->full_name ?? $order->user->name }}</div>
                            <div class="row-col col-products">
                                {{ $order->orderItems->count() }} item(s)
                            </div>
                            <div class="row-col col-total">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</div>
                            <div class="row-col col-payment">{{ $order->metodePembayaran->nama ?? '-' }}</div>
                        </div>
                    @empty
                        <div class="no-data">
                            <p>Belum ada transaksi pada periode ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Top Products Section --}}
        <div class="top-products-section">
            <h2 class="section-title">🔥 Top 5 Produk Terlaris</h2>
            <div class="top-products-list">
                @forelse($topProducts as $index => $item)
                    <div class="top-product-item">
                        <div class="product-rank">{{ $index + 1 }}</div>
                        <div class="product-info">
                            <div class="product-name">{{ $item->produk->nama_produk ?? 'Unknown Product' }}</div>
                            <div class="product-stats">
                                <span class="stat-sold">{{ $item->total_sold }} terjual</span>
                                <span class="stat-revenue">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="no-data">
                        <p>Belum ada data produk terlaris.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Chart.js Library --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    {{-- JavaScript --}}
    <script>
        function applyPeriodFilter(period) {
            window.location.href = "{{ route('admin.reports.sales') }}?period=" + period;
        }
        
        // Chart data from backend
        const chartData = @json($chartData);
        const labels = chartData.map(item => item.date);
        const data = chartData.map(item => item.total);
        
        // Create chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: data,
                    borderColor: '#a89486',
                    backgroundColor: 'rgba(168, 148, 134, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection