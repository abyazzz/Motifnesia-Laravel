@extends('customer.layouts.mainLayout')

@section('container')

<div class="min-h-screen pt-20 px-8" style="background-color: #f5f5f5;">
    <div class="max-w-4xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 pt-3">
            <h2 class="text-3xl font-bold">Notifikasi</h2>
            @if($notifications->where('is_read', false)->count() > 0)
                <form action="{{ route('customer.notifications.markAllRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-orange-700 text-white rounded-lg hover:bg-orange-800 transition-colors text-sm font-semibold">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>

        {{-- Notifications List --}}
        @forelse ($notifications as $notif)
            <div class="bg-white rounded-lg p-5 mb-3 relative {{ $notif->is_read ? '' : 'border-l-4' }}" 
                 style="box-shadow: 0 2px 8px rgba(0,0,0,0.08); {{ !$notif->is_read ? 'border-color: #f97316;' : '' }}">
                
                <div class="flex gap-4">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center text-2xl"
                         style="background-color: {{ $notif->type === 'order' ? '#dbeafe' : ($notif->type === 'return' ? '#fef3c7' : '#fce7f3') }};">
                        @if($notif->type === 'order')
                            📦
                        @elseif($notif->type === 'return')
                            ↩️
                        @elseif($notif->type === 'review')
                            ⭐
                        @else
                            🔔
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-lg {{ !$notif->is_read ? 'text-orange-700' : 'text-gray-800' }}">
                                {{ $notif->title }}
                            </h3>
                            <span class="text-xs text-gray-500">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>

                        <p class="text-gray-700 text-sm mb-3">{{ $notif->message }}</p>

                        {{-- Product Details untuk notifikasi retur --}}
                        @if($notif->type === 'return' && $notif->data)
                            @php
                                $data = json_decode($notif->data, true);
                            @endphp
                            <div class="bg-gray-50 rounded-lg p-3 mt-3 flex gap-3">
                                <img src="{{ asset('images/' . $data['produk_gambar']) }}" 
                                     alt="{{ $data['produk_nama'] }}"
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <p class="font-semibold text-sm mb-1">{{ $data['produk_nama'] }}</p>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $data['status'] === 'Disetujui' ? 'bg-green-100 text-green-700' : ($data['status'] === 'Ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                            {{ $data['status'] }}
                                        </span>
                                        <span class="text-xs text-gray-600">{{ $data['reason'] }}</span>
                                    </div>
                                    @if(isset($data['refund_amount']) && $data['refund_amount'])
                                        <p class="text-sm font-semibold text-green-600">Refund: Rp {{ number_format($data['refund_amount'], 0, ',', '.') }}</p>
                                    @endif
                                    @if(isset($data['admin_note']) && $data['admin_note'])
                                        <p class="text-xs text-gray-600 mt-1">💬 Admin: {{ $data['admin_note'] }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Action Button --}}
                        @if($notif->link)
                            <a href="{{ $notif->link }}" class="inline-block mt-3 px-4 py-2 rounded-lg text-sm font-semibold text-white transition-colors" style="background-color: #8B4513;">
                                @if($notif->type === 'return')
                                    Lihat Detail Retur
                                @else
                                    Lihat Detail
                                @endif
                            </a>
                        @endif
                    </div>

                    {{-- Unread Indicator --}}
                    @if(!$notif->is_read)
                        <div class="absolute top-5 right-5 w-3 h-3 bg-orange-500 rounded-full"></div>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg p-12 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="text-6xl mb-4">🔕</div>
                <h3 class="text-xl font-bold mb-2 text-gray-800">Belum Ada Notifikasi</h3>
                <p class="text-gray-600">Notifikasi pesanan dan retur Anda akan muncul di sini</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection