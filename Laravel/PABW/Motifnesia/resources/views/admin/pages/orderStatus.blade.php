@extends('admin.layouts.mainLayout')

@section('title', 'Status Pesanan Pelanggan')

@section('content')
<div class="p-6 max-w-7xl">
    {{-- Search & Filter Bar --}}
    <div class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <div class="flex items-center gap-4">
                <div class="relative flex-1">
                    <input type="text" 
                           id="searchOrder"
                           placeholder="Cari berdasarkan nama pelanggan atau alamat..." 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <select id="statusFilter" 
                        class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white min-w-[200px]">
                    <option value="all">Semua Status</option>
                    @foreach($deliveryStatuses as $status)
                        <option value="{{ $status->nama_status }}">{{ $status->nama_status }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table Header -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 px-6 py-3 text-sm font-semibold text-gray-700">
                <div class="col-span-2">Pelanggan</div>
                <div class="col-span-3">Detail Produk</div>
                <div class="col-span-1">Total</div>
                <div class="col-span-2">Alamat</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-2 text-center">Aksi</div>
            </div>
        </div>

        <!-- Table Body -->
        <div class="divide-y divide-gray-200">
            @forelse($orders as $order)
                <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-gray-50 transition-colors order-row" 
                     data-customer="{{ $order->user->full_name ?? $order->user->name }}" 
                     data-address="{{ $order->alamat }}" 
                     data-status="{{ $order->deliveryStatus->nama_status }}">
                    <!-- Customer -->
                    <div class="col-span-2">
                        <p class="font-semibold text-gray-800 text-sm">{{ $order->user->full_name ?? $order->user->name }}</p>
                        <p class="text-xs text-gray-500">Order #{{ $order->id }}</p>
                    </div>

                    <!-- Products -->
                    <div class="col-span-3 text-sm">
                        @foreach($order->orderItems as $item)
                            <div class="mb-2">
                                <p class="text-gray-800">• {{ $item->nama_produk }}
                                    @if($item->ukuran)
                                        <span class="text-gray-500">({{ $item->ukuran }})</span>
                                    @endif
                                    <span class="text-gray-600">× {{ $item->qty }}</span>
                                </p>
                                <p class="text-xs text-gray-500">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Total -->
                    <div class="col-span-1 text-sm">
                        <p class="font-semibold text-gray-800">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</p>
                    </div>

                    <!-- Address -->
                    <div class="col-span-2 text-sm text-gray-700">
                        {{ Str::limit($order->alamat, 50) }}
                    </div>

                    <!-- Status -->
                    <div class="col-span-2">
                        @php
                            $statusColors = [
                                'Pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                'Diproses' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'Dikemas' => 'bg-purple-100 text-purple-800 border-purple-300',
                                'Dalam Perjalanan' => 'bg-orange-100 text-orange-800 border-orange-300',
                                'Sampai' => 'bg-green-100 text-green-800 border-green-300',
                            ];
                            $currentStatus = $order->deliveryStatus->nama_status;
                            $statusClass = $statusColors[$currentStatus] ?? 'bg-gray-100 text-gray-800 border-gray-300';
                        @endphp
                        <select class="status-dropdown w-full px-3 py-2 border rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-amber-500 cursor-pointer {{ $statusClass }}" 
                                data-order-id="{{ $order->id }}">
                            @foreach($deliveryStatuses as $status)
                                <option value="{{ $status->id }}" 
                                        @if($order->delivery_status_id == $status->id) selected @endif>
                                    {{ $status->nama_status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="col-span-2 flex gap-2 justify-center">
                        <button onclick="showPaymentProof('{{ $order->payment_number }}')"
                                class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors">
                            💳 Bukti
                        </button>
                        <button onclick="updateStatus({{ $order->id }})"
                                class="px-3 py-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white text-xs font-medium rounded-lg transition-colors">
                            ✓ Update
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">📦</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-600">Belum ada pesanan yang perlu diproses.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-24 right-6 bg-green-600 text-white px-6 py-4 rounded-lg shadow-xl transform translate-x-full transition-transform duration-300 z-50"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchOrder');
    const statusFilter = document.getElementById('statusFilter');
    
    function filterOrders() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStatus = statusFilter.value;
        
        document.querySelectorAll('.order-row').forEach(row => {
            const customerName = row.getAttribute('data-customer').toLowerCase();
            const address = row.getAttribute('data-address').toLowerCase();
            const status = row.getAttribute('data-status');
            
            const matchSearch = customerName.includes(searchTerm) || address.includes(searchTerm);
            const matchStatus = selectedStatus === 'all' || status === selectedStatus;
            
            if (matchSearch && matchStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterOrders);
    }
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterOrders);
    }
});

function showPaymentProof(paymentNumber) {
    const toast = document.getElementById('toast');
    toast.textContent = `Bukti Pembayaran: ${paymentNumber}`;
    toast.classList.remove('translate-x-full');
    
    setTimeout(() => {
        toast.classList.add('translate-x-full');
    }, 3000);
}

function updateStatus(orderId) {
    const dropdown = document.querySelector(`.status-dropdown[data-order-id="${orderId}"]`);
    const statusId = dropdown.value;
    
    fetch(`/admin/order-status/${orderId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            delivery_status_id: statusId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const toast = document.getElementById('toast');
            toast.textContent = 'Status berhasil diperbarui!';
            toast.classList.remove('translate-x-full');
            
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                location.reload();
            }, 1500);
        } else {
            alert('Gagal memperbarui status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan.');
    });
}
</script>
@endsection