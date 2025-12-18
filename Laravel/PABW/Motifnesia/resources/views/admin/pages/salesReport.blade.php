@extends('admin.layouts.mainLayout')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="p-6 max-w-7xl">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div></div>
            
            {{-- Filter dan Export --}}
            <div class="flex items-center gap-3">
                <select id="period-filter" 
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white text-sm"
                        onchange="applyPeriodFilter(this.value)">
                    <option value="today" {{ $currentPeriod == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="7" {{ $currentPeriod == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30" {{ $currentPeriod == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="month" {{ $currentPeriod == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                </select>
                
                <a href="{{ route('admin.reports.export') }}?period={{ $currentPeriod }}" 
                   class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>

        {{-- Metrics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">💰</div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 mb-1">Total Revenue</div>
                        <div class="text-lg font-bold text-gray-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">🛒</div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 mb-1">Total Orders</div>
                        <div class="text-lg font-bold text-gray-800">{{ $totalOrders }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">📦</div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 mb-1">Products Sold</div>
                        <div class="text-lg font-bold text-gray-800">{{ $totalProductsSold }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">📊</div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 mb-1">Avg Order Value</div>
                        <div class="text-lg font-bold text-gray-800">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart Section --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">📈 Grafik Penjualan</h2>
            <div class="relative h-[300px]">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">📋 Detail Transaksi</h2>
            <div class="overflow-x-auto">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg px-4 py-3 mb-2">
                    <div class="grid grid-cols-12 gap-4 text-sm font-semibold text-gray-700">
                        <div class="col-span-2">Order</div>
                        <div class="col-span-2">Tanggal</div>
                        <div class="col-span-3">Customer</div>
                        <div class="col-span-2">Produk</div>
                        <div class="col-span-2 text-right">Total</div>
                        <div class="col-span-1">Payment</div>
                    </div>
                </div>
                
                <div class="divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <div class="grid grid-cols-12 gap-4 px-4 py-3 hover:bg-gray-50 transition-colors text-sm">
                            <div class="col-span-2 font-medium text-gray-800">#{{ $order->order_number }}</div>
                            <div class="col-span-2 text-gray-600">{{ $order->created_at->format('d M Y H:i') }}</div>
                            <div class="col-span-3 text-gray-700">{{ $order->user->full_name ?? $order->user->name }}</div>
                            <div class="col-span-2 text-gray-600">{{ $order->orderItems->count() }} item(s)</div>
                            <div class="col-span-2 text-right font-semibold text-gray-800">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</div>
                            <div class="col-span-1 text-gray-600">{{ $order->metodePembayaran->nama ?? '-' }}</div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-5xl mb-3">📭</div>
                            <p class="text-gray-600">Belum ada transaksi pada periode ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Top Products Section --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">🔥 Top 5 Produk Terlaris</h2>
            <div class="space-y-3">
                @forelse($topProducts as $index => $item)
                    <div class="flex items-center gap-4 p-3 bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg hover:shadow-md transition-shadow">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-600 to-orange-600 text-white rounded-full flex items-center justify-center font-bold text-lg flex-shrink-0">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <div class="font-semibold text-gray-800 mb-1">{{ $item->produk->nama_produk ?? 'Unknown Product' }}</div>
                            <div class="flex items-center gap-4 text-sm">
                                <span class="text-gray-600">{{ $item->total_sold }} terjual</span>
                                <span class="font-semibold text-amber-700">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-5xl mb-3">📦</div>
                        <p class="text-gray-600">Belum ada data produk terlaris.</p>
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