@extends('customer.layouts.mainLayout')

@section('container')
<div class="payment-confirm-container" style="max-width:600px;margin:40px auto;padding:30px;">
    <form id="paymentForm" method="POST" action="{{ route('customer.payment.final') }}">
        @csrf
        <h3>Konfirmasi Pembayaran</h3>
        <div class="mb-3">
            <label>Alamat:</label>
            <div class="form-control">{{ $session['alamat'] ?? '-' }}</div>
        </div>
        <div class="mb-3">
            <label>Produk:</label>
            <ul>
                @foreach(($session['produk'] ?? []) as $item)
                    <li>{{ $item['produk']['nama_produk'] }} - {{ $item['ukuran'] }} x{{ $item['qty'] }} (Rp{{ number_format($item['produk']['harga'], 0, ',', '.') }})</li>
                @endforeach
            </ul>
        </div>
        <div class="mb-3">
            <label>Metode Pengiriman:</label>
            <div class="form-control">{{ $metodePengiriman->nama_pengiriman ?? '-' }}</div>
        </div>
        <div class="mb-3">
            <label>Metode Pembayaran:</label>
            <div class="form-control">{{ $metodePembayaran->nama_pembayaran ?? '-' }}</div>
        </div>
        <div class="mb-3">
            <label>Total:</label>
            <div class="form-control">Rp{{ number_format($session['total'] ?? 0, 0, ',', '.') }}</div>
        </div>
        <button type="submit" class="btn btn-success" style="width:100%;font-size:1.2em;">Bayar</button>
    </form>
</div>
@endsection