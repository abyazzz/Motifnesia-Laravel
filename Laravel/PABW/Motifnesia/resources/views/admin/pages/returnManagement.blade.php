@extends('admin.layouts.mainLayout')

@section('title', 'Kelola Retur')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/returnManagement.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/returnManagement_responsive.css') }}">

    <div class="return-management-container">
        <!-- Header -->
        <header class="management-header">
            <h1 class="header-title">Kelola Retur Produk</h1>
        </header>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon all">📦</div>
                <div class="stat-content">
                    <h3>Total Retur</h3>
                    <p class="stat-value">{{ $counts['all'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon pending">⏳</div>
                <div class="stat-content">
                    <h3>Pending</h3>
                    <p class="stat-value">{{ $counts['Pending'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon approved">✅</div>
                <div class="stat-content">
                    <h3>Disetujui</h3>
                    <p class="stat-value">{{ $counts['Disetujui'] }}</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon processing">🔄</div>
                <div class="stat-content">
                    <h3>Diproses</h3>
                    <p class="stat-value">{{ $counts['Diproses'] }}</p>
                </div>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <a href="{{ route('admin.returns.index', ['status' => 'all']) }}" 
               class="tab-item {{ $currentFilter === 'all' ? 'active' : '' }}">
                Semua ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Pending']) }}" 
               class="tab-item {{ $currentFilter === 'Pending' ? 'active' : '' }}">
                Pending ({{ $counts['Pending'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Disetujui']) }}" 
               class="tab-item {{ $currentFilter === 'Disetujui' ? 'active' : '' }}">
                Disetujui ({{ $counts['Disetujui'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Ditolak']) }}" 
               class="tab-item {{ $currentFilter === 'Ditolak' ? 'active' : '' }}">
                Ditolak ({{ $counts['Ditolak'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Diproses']) }}" 
               class="tab-item {{ $currentFilter === 'Diproses' ? 'active' : '' }}">
                Diproses ({{ $counts['Diproses'] }})
            </a>
            <a href="{{ route('admin.returns.index', ['status' => 'Selesai']) }}" 
               class="tab-item {{ $currentFilter === 'Selesai' ? 'active' : '' }}">
                Selesai ({{ $counts['Selesai'] }})
            </a>
        </div>

        <!-- Returns List -->
        <main class="returns-list">
            @forelse($returns as $return)
                @include('admin.components.returnCard', ['return' => $return])
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📭</div>
                    <h3>Tidak Ada Retur</h3>
                    <p>{{ $currentFilter === 'all' ? 'Belum ada pengajuan retur dari customer.' : 'Tidak ada retur dengan status ' . $currentFilter }}</p>
                </div>
            @endforelse
        </main>

        <!-- Pagination -->
        @if($returns->hasPages())
            <footer class="pagination-footer">
                {{ $returns->appends(['status' => $currentFilter])->links() }}
            </footer>
        @endif
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast"></div>

    <script>
    function showToast(message, type = 'info') {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.className = `toast toast-${type} show`;
        
        setTimeout(() => {
            toast.className = 'toast';
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