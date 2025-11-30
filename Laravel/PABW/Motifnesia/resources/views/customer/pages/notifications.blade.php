@extends('customer.layouts.mainLayout')

@section('container')
    <div class="notifications-container">
        <h1>Notifikasi Kamu</h1>
        
        <div class="notifications-list">
            @forelse ($notifications as $notification)
                <div class="notification-card">
                    <div class="notification-header">
                        {{-- Icon Orange sesuai screenshot (kita pakai Font Awesome) --}}
                        <i class="fas fa-circle notification-icon"></i> 
                        <span class="notification-status">{{ $notification['status'] }}</span>
                    </div>
                    <p class="notification-time">{{ date('d-M-Y H:i', strtotime($notification['timestamp'])) }}</p>
                    
                    <ul class="notification-details-list">
                        @foreach ($notification['details'] as $detail)
                            <li>
                                {{ $detail['nama'] }} 
                                ({{ number_format($detail['harga'], 0, ',', '.') }}) &times; {{ $detail['jumlah'] }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @empty
                <p class="empty-list">Tidak ada notifikasi baru.</p>
            @endforelse
        </div>
    </div>
@endsection