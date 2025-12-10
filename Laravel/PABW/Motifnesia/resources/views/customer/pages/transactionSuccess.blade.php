@extends('customer.layouts.mainLayout')

@section('container')
<div class="success-container" style="max-width:600px;margin:80px auto;padding:40px;text-align:center;">
    
    {{-- Success Icon --}}
    <div style="background:#4BB543;width:120px;height:120px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 30px;">
        <svg xmlns="http://www.w3.org/2000/svg" width="70" height="70" fill="white" viewBox="0 0 16 16">
            <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
        </svg>
    </div>

    {{-- Success Message --}}
    <h2 style="font-size:28px;font-weight:700;color:#333;margin-bottom:15px;">Pembayaran Sukses!</h2>
    <p style="font-size:16px;color:#666;margin-bottom:30px;">Pesananmu sedang diproses. Kami akan mengirimkan konfirmasi melalui email.</p>

    {{-- Order Info --}}
    <div style="background:#f8f9fa;border-radius:12px;padding:20px;margin-bottom:30px;text-align:left;">
        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #dee2e6;">
            <span style="color:#666;">Nomor Pesanan:</span>
            <strong style="color:#333;">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #dee2e6;">
            <span style="color:#666;">Total Pembayaran:</span>
            <strong style="color:#8B4513;font-size:18px;">Rp. {{ number_format($order->total_bayar, 0, ',', '.') }}</strong>
        </div>
        <div style="display:flex;justify-content:space-between;padding:10px 0;">
            <span style="color:#666;">Status:</span>
            <span style="background:#FFC107;color:#fff;padding:4px 12px;border-radius:20px;font-size:12px;font-weight:600;">{{ ucfirst($order->status) }}</span>
        </div>
    </div>

    {{-- Button Back to Home --}}
    <a href="{{ route('customer.home') }}" style="display:inline-block;background:#8B4513;color:white;padding:15px 50px;border-radius:8px;font-size:16px;font-weight:600;text-decoration:none;transition:all 0.3s;">
        Kembali ke Beranda
    </a>

</div>
@endsection
