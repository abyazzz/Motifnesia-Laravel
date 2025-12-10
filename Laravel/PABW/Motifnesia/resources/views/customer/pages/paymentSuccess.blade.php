@extends('customer.layouts.mainLayout')

@section('container')
<div class="success-container" style="max-width:500px;margin:60px auto;padding:30px;text-align:center;">
    <div style="font-size:3em;color:#4BB543;margin-bottom:20px;">
        <i class="fas fa-check-circle"></i>
    </div>
    <h2>Pembayaran Berhasil!</h2>
    <p>Terima kasih, pesanan Anda telah diterima dan sedang diproses.</p>
    <a href="{{ route('customer.home') }}" class="btn btn-primary" style="margin-top:20px;">Kembali ke Beranda</a>
</div>
@endsection
