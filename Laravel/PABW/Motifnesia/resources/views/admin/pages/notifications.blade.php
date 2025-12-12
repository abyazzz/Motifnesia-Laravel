@extends('admin.layouts.mainLayout')

@section('title', 'Notifikasi Sistem')

@section('content')
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
<div class="notifications-container">
    <!-- Header -->
    <div class="notifications-header">
        <h1 class="page-title">Notifikasi</h1>
        <div class="header-actions">
            <button class="btn-action" id="markAllReadBtn" onclick="markAllRead()">
                <i class="icon">✓</i> Tandai Semua Dibaca
            </button>
            <button class="btn-action" id="clearReadBtn" onclick="clearRead()">
                <i class="icon">🗑️</i> Hapus Yang Dibaca
            </button>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-icon unread">🔔</div>
            <div class="metric-content">
                <h3>Belum Dibaca</h3>
                <p class="metric-value">{{ $unreadCount }}</p>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-icon today">📅</div>
            <div class="metric-content">
                <h3>Hari Ini</h3>
                <p class="metric-value">{{ $todayCount }}</p>
            </div>
        </div>
        
        <div class="metric-card">
            <div class="metric-icon total">📊</div>
            <div class="metric-content">
                <h3>Total Notifikasi</h3>
                <p class="metric-value">{{ $totalCount }}</p>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}" 
           class="tab-item {{ $currentFilter === 'all' ? 'active' : '' }}">
            Semua ({{ $totalCount }})
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}" 
           class="tab-item {{ $currentFilter === 'unread' ? 'active' : '' }}">
            Belum Dibaca ({{ $unreadCount }})
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'order']) }}" 
           class="tab-item {{ $currentFilter === 'order' ? 'active' : '' }}">
            🛒 Pesanan
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'stock']) }}" 
           class="tab-item {{ $currentFilter === 'stock' ? 'active' : '' }}">
            📦 Stok
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'review']) }}" 
           class="tab-item {{ $currentFilter === 'review' ? 'active' : '' }}">
            ⭐ Review
        </a>
        <a href="{{ route('admin.notifications.index', ['filter' => 'system']) }}" 
           class="tab-item {{ $currentFilter === 'system' ? 'active' : '' }}">
            ⚙️ Sistem
        </a>
    </div>

    <!-- Search Bar -->
    <div class="search-container">
        <form action="{{ route('admin.notifications.index') }}" method="GET" class="search-form">
            <input type="hidden" name="filter" value="{{ $currentFilter }}">
            <input type="text" 
                   name="search" 
                   placeholder="Cari notifikasi..." 
                   value="{{ $currentSearch }}"
                   class="search-input">
            <button type="submit" class="btn-search">
                🔍 Cari
            </button>
            @if($currentSearch)
                <a href="{{ route('admin.notifications.index', ['filter' => $currentFilter]) }}" 
                   class="btn-clear">
                    ✕ Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Notifications List -->
    <div class="notifications-list">
        @forelse($notifications as $notification)
            @include('admin.components.notificationItem', ['notification' => $notification])
        @empty
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <h3>Tidak ada notifikasi</h3>
                <p>{{ $currentFilter === 'unread' ? 'Semua notifikasi sudah dibaca!' : 'Belum ada notifikasi untuk ditampilkan.' }}</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="pagination-container">
            {{ $notifications->appends(['filter' => $currentFilter, 'search' => $currentSearch])->links() }}
        </div>
    @endif
</div>

<!-- Toast Notification -->
<div id="toast" class="toast"></div>

<script>
function toggleRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/toggle-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update counts and badges
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}

function deleteNotification(notificationId) {
    if (!confirm('Yakin ingin menghapus notifikasi ini?')) {
        return;
    }

    fetch(`/admin/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}

function markAllRead() {
    if (!confirm('Tandai semua notifikasi sebagai sudah dibaca?')) {
        return;
    }

    fetch('{{ route('admin.notifications.markAllRead') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(() => location.reload())
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}

function clearRead() {
    if (!confirm('Hapus semua notifikasi yang sudah dibaca?')) {
        return;
    }

    fetch('{{ route('admin.notifications.clearRead') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(() => location.reload())
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}

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
</script>
@endsection
