@extends('admin.layouts.mainLayout')

@section('title', 'Daftar Pelanggan')

@section('content')
    <div class="p-6">
        {{-- Success Alert --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Search Bar --}}
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="relative">
                    <input type="text" 
                           id="searchCustomer"
                           placeholder="Cari pelanggan berdasarkan nama, email, username..." 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Customer Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            {{-- Table Header --}}
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
                <div class="grid grid-cols-12 gap-4 px-6 py-4 font-semibold text-gray-700">
                    <div class="col-span-2">Username</div>
                    <div class="col-span-3">Nama Lengkap</div>
                    <div class="col-span-3">Email</div>
                    <div class="col-span-2 text-center">Total Produk</div>
                    <div class="col-span-2 text-center">Aksi</div>
                </div>
            </div>

            {{-- Table Body --}}
            <div class="divide-y divide-gray-200">
                @forelse($customers as $customer)
                    <div class="grid grid-cols-12 gap-4 px-6 py-4 hover:bg-gray-50 transition-colors duration-150 customer-row" data-customer='@json($customer)'>
                        <div class="col-span-2 flex items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center text-white font-semibold shadow-md">
                                    {{ strtoupper(substr($customer['username'] ?? $customer['email'], 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $customer['username'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-span-3 flex items-center">
                            <span class="text-gray-700">{{ $customer['full_name'] ?? 'N/A' }}</span>
                        </div>
                        <div class="col-span-3 flex items-center">
                            <span class="text-gray-600">{{ $customer['email'] }}</span>
                        </div>
                        <div class="col-span-2 flex items-center justify-center">
                            <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-semibold">
                                {{ $customer['total_products'] ?? 0 }} Produk
                            </span>
                        </div>
                        <div class="col-span-2 flex items-center justify-center">
                            <button class="view-detail-btn p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail" data-customer-id="{{ $customer['id'] }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <div class="text-6xl mb-4">👥</div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Pelanggan</h3>
                        <p class="text-gray-600">Belum ada pelanggan yang melakukan pembelian.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modal Detail Customer --}}
    <div id="customerDetailModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Detail Pelanggan
                </h2>
                <button id="closeDetailModal" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                <div id="customerDetailContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchCustomer');
            const detailModal = document.getElementById('customerDetailModal');
            const closeDetailModal = document.getElementById('closeDetailModal');
            const customerDetailContent = document.getElementById('customerDetailContent');
            
            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    document.querySelectorAll('.customer-row').forEach(row => {
                        const customer = JSON.parse(row.getAttribute('data-customer'));
                        const searchableText = [
                            customer.username || '',
                            customer.name || '',
                            customer.email || ''
                        ].join(' ').toLowerCase();
                        
                        if (searchableText.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // View detail button handlers
            document.querySelectorAll('.view-detail-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const customerId = this.getAttribute('data-customer-id');
                    const customerRow = this.closest('.customer-row');
                    const customer = JSON.parse(customerRow.getAttribute('data-customer'));
                    
                    showCustomerDetail(customer);
                });
            });

            // Close modal
            closeDetailModal.addEventListener('click', function() {
                detailModal.classList.add('hidden');
            });

            // Close modal when clicking outside
            detailModal.addEventListener('click', function(e) {
                if (e.target === detailModal) {
                    detailModal.classList.add('hidden');
                }
            });

            function showCustomerDetail(customer) {
                let ordersHtml = '';
                if (customer.orders && customer.orders.length > 0) {
                    ordersHtml = customer.orders.map(order => `
                        <div class="bg-gray-50 rounded-lg p-4 mb-3">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-800">Order #${order.order_number}</h4>
                                    <p class="text-sm text-gray-600">${new Date(order.created_at).toLocaleDateString('id-ID')}</p>
                                </div>
                                <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-sm font-semibold">
                                    Rp ${parseInt(order.total_bayar).toLocaleString('id-ID')}
                                </span>
                            </div>
                            <div class="space-y-1">
                                ${order.orderItems ? order.orderItems.map(item => `
                                    <div class="flex justify-between text-sm">
                                        <span>${item.nama_produk} (${item.ukuran}) x${item.qty}</span>
                                        <span>Rp ${parseInt(item.subtotal).toLocaleString('id-ID')}</span>
                                    </div>
                                `).join('') : ''}
                            </div>
                        </div>
                    `).join('');
                } else {
                    ordersHtml = '<p class="text-gray-500 italic">Belum ada riwayat pembelian</p>';
                }

                customerDetailContent.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Pelanggan</h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        ${customer.username ? customer.username.charAt(0).toUpperCase() : customer.email.charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">${customer.full_name || customer.username || 'N/A'}</p>
                                        <p class="text-sm text-gray-600">${customer.username || 'N/A'}</p>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="text-gray-800">${customer.email}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Total Produk Dibeli</label>
                                    <p class="text-gray-800">${customer.total_products || 0} produk</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order History -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pembelian</h3>
                            <div class="max-h-96 overflow-y-auto">
                                ${ordersHtml}
                            </div>
                        </div>
                    </div>
                `;
                
                detailModal.classList.remove('hidden');
            }
        });
    </script>
@endsection