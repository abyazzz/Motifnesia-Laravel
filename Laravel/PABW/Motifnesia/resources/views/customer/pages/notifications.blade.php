@extends('customer.layouts.mainLayout')

@section('container')
<div style="max-width:800px;margin:80px auto;padding:20px;">
    {{-- Header --}}
    <div style="background:#D2691E;color:white;padding:15px;border-radius:8px 8px 0 0;text-align:center;">
        <h1 style="font-size:22px;font-weight:600;margin:0;">Notifikasi</h1>
    </div>

    {{-- Notifications List --}}
    <div style="background:white;border-radius:0 0 8px 8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        @forelse ($notifications as $statusName => $historyItems)
            @foreach($historyItems as $history)
                <div style="border-bottom:1px solid #eee;padding:20px;">
                    {{-- Status Header --}}
                    <div style="font-size:16px;font-weight:600;color:#333;margin-bottom:8px;">
                        Pesanan Anda Sedang {{ $statusName }}
                    </div>
                    
                    {{-- Product Details --}}
                    @foreach($history->order->orderItems as $item)
                        <div style="color:#666;font-size:14px;margin-bottom:3px;">
                            {{ $item->nama_produk }} - {{ $item->ukuran }} - {{ $item->qty }}x
                        </div>
                        <div style="color:#666;font-size:14px;margin-bottom:8px;">
                            Rp. {{ number_format($item->harga, 0, ',', '.') }}
                        </div>
                    @endforeach
                </div>
            @endforeach
        @empty
            <div style="padding:40px;text-align:center;color:#999;">
                Belum ada notifikasi
            </div>
        @endforelse
    </div>
</div>
@endsection