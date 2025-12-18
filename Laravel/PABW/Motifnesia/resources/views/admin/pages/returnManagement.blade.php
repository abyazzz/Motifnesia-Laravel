@extends('admin.layouts.mainLayout')

@section('title', 'Kelola Retur')

@section('content')
    <div class="p-6 max-w-7xl">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">📦</div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 mb-1">Total Retur</div>
                        <div class="text-lg font-bold text-gray-800">{{ $counts['all'] }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">⏳</div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 mb-1">Pending</div>
                        <div class="text-lg font-bold text-gray-800">{{ $counts['Pending'] }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">✅</div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 mb-1">Disetujui</div>
                        <div class="text-lg font-bold text-gray-800">{{ $counts['Disetujui'] }}</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-3">
                    <div class="text-3xl">🔄</div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-600 mb-1">Diproses</div>
                        <div class="text-lg font-bold text-gray-800">{{ $counts['Diproses'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex flex-wrap gap-2 mb-6 bg-white rounded-lg shadow-sm border border-gray-200 p-3">
            <a href="{{ route('admin.returns.index', ['status' => 'all']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentFilter === 'all' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Pending']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentFilter === 'Pending' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Pending ({{ $counts['Pending'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Disetujui']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentFilter === 'Disetujui' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Disetujui ({{ $counts['Disetujui'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Ditolak']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentFilter === 'Ditolak' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Ditolak ({{ $counts['Ditolak'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Diproses']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentFilter === 'Diproses' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Diproses ({{ $counts['Diproses'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Selesai']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentFilter === 'Selesai' ? 'bg-gradient-to-r from-amber-600 to-orange-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Selesai ({{ $counts['Selesai'] }})
            </a>
        </div>

        <!-- Returns List -->
        <div class="space-y-4">
            @forelse($returns as $return)
                @include('admin.components.returnCard', ['return' => $return])
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 text-center py-20">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Tidak Ada Retur</h3>
                    <p class="text-gray-600">{{ $currentFilter === 'all' ? 'Belum ada pengajuan retur dari customer.' : 'Tidak ada retur dengan status ' . $currentFilter }}</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($returns->hasPages())
            <div class="mt-6">
                {{ $returns->appends(['status' => $currentFilter])->links() }}
            </div>
        @endif
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="fixed top-24 right-6 bg-green-600 text-white px-6 py-4 rounded-lg shadow-xl transform translate-x-full transition-transform duration-300 z-50"></div>

    <script>
    function showToast(message, type = 'info') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        if (type === 'success') {
            toast.className = 'fixed top-24 right-6 bg-green-600 text-white px-6 py-4 rounded-lg shadow-xl transition-transform duration-300 z-50';
        } else {
            toast.className = 'fixed top-24 right-6 bg-red-600 text-white px-6 py-4 rounded-lg shadow-xl transition-transform duration-300 z-50';
        }
        
        setTimeout(() => {
            toast.classList.add('translate-x-full');
        }, 3000);
    }

    // Show success message if present
    @if(session('success'))
        showToast('{{ session('success') }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session('error') }}', 'error');
    @endif
    </script>
@endsection