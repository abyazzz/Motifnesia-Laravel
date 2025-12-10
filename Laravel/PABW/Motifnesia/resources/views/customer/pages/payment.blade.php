@extends('customer.layouts.mainLayout')

@section('container')
<div class="payment-container" style="max-width:900px;margin:40px auto;padding:20px;">
    
    <h2 style="font-size:24px;font-weight:600;margin-bottom:30px;text-align:center;">Transaksi</h2>

    {{-- Header Bayar Sebelum --}}
    <div class="payment-header" style="background:#FFF8DC;border:1px solid #ddd;border-radius:12px;padding:25px;margin-bottom:20px;text-align:center;">
        <div style="display:flex;align-items:center;justify-content:center;gap:15px;margin-bottom:10px;">
            <div style="background:#FFD700;width:60px;height:60px;border-radius:50%;display:flex;align-items:center;justify-content:center;">
                <i class="fa fa-clock" style="font-size:30px;color:#8B4513;"></i>
            </div>
            <div style="text-align:left;">
                <h3 style="font-size:18px;font-weight:600;margin:0;">Bayar sebelum</h3>
                <p id="deadline_display" style="font-size:16px;color:#333;margin:0;">{{ $paymentDeadline->format('d F Y H:i') }} WIB</p>
            </div>
        </div>
        <div id="countdown_timer" style="font-size:20px;color:#8B4513;font-weight:600;">23:59:59</div>
    </div>

    {{-- Nomor Rekening & Info Payment --}}
    <div class="section-box" style="background:white;border:1px solid #ddd;border-radius:12px;padding:20px;margin-bottom:20px;">
        <p style="font-size:14px;color:#333;margin-bottom:15px;">{{ $checkoutData['created_at']->format('d F Y H:i') }} WIB</p>
        <h3 style="font-size:32px;font-weight:700;letter-spacing:2px;margin-bottom:20px;text-align:center;">8887867867555700</h3>
        
        <div style="margin-top:20px;">
            <h4 style="font-size:16px;font-weight:600;margin-bottom:10px;">Total Tagihan</h4>
            <p style="font-size:24px;font-weight:700;color:#8B4513;">Rp. {{ number_format($checkoutData['total_bayar'], 0, ',', '.') }}</p>
            <a href="#" id="link_detail" style="font-size:14px;color:#007bff;text-decoration:underline;cursor:pointer;">Lihat Detail</a>
        </div>
    </div>

    {{-- Detail Hidden (Toggle) --}}
    <div id="detail_box" class="section-box" style="display:none;background:white;border:1px solid #ddd;border-radius:12px;padding:20px;margin-bottom:20px;">
        <h4 style="font-size:16px;font-weight:600;margin-bottom:15px;">Rincian Belanja</h4>
        
        @foreach($checkoutData['products'] as $product)
        <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:14px;border-bottom:1px solid #eee;">
            <span>{{ $product['nama'] }} ({{ $product['ukuran'] }}) x{{ $product['qty'] }}</span>
            <span>Rp. {{ number_format($product['subtotal'], 0, ',', '.') }}</span>
        </div>
        @endforeach
        
        <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:14px;">
            <span>Subtotal Produk:</span>
            <span>Rp. {{ number_format($checkoutData['subtotal_produk'], 0, ',', '.') }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:14px;">
            <span>Ongkos Kirim ({{ $checkoutData['metode_pengiriman']['nama'] }}):</span>
            <span>Rp. {{ number_format($checkoutData['total_ongkir'], 0, ',', '.') }}</span>
        </div>
        <hr style="margin:10px 0;">
        <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:16px;font-weight:600;">
            <span>Total:</span>
            <span>Rp. {{ number_format($checkoutData['total_bayar'], 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Form Input Nomor Pembayaran --}}
    <form id="form_payment" action="{{ route('customer.payment.store') }}" method="POST" onsubmit="console.log('Form submitting...'); return true;">
        @csrf
        <div class="section-box" style="background:white;border:1px solid #ddd;border-radius:12px;padding:20px;margin-bottom:20px;">
            <label for="payment_number" style="font-size:14px;font-weight:600;margin-bottom:10px;display:block;">Masukkan Nomor Pembayaran</label>
            <input type="text" name="payment_number" id="payment_number" placeholder="Contoh: 8887867867555700" 
                   style="width:100%;padding:12px;border:1px solid #ddd;border-radius:8px;font-size:14px;" required>
        </div>

        {{-- Button Bayar --}}
        <button type="submit" style="width:100%;background:#8B4513;color:white;border:none;border-radius:8px;padding:15px;font-size:16px;font-weight:600;cursor:pointer;">
            Konfirmasi Pembayaran
        </button>
    </form>

</div>

{{-- Modal Success --}}
<div class="modal fade" id="modalSuccess" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px;text-align:center;padding:20px;">
            <div class="modal-body">
                <div style="font-size:60px;color:#4BB543;margin-bottom:20px;">
                    <i class="fa fa-check-circle"></i>
                </div>
                <h4 style="font-size:20px;font-weight:600;margin-bottom:10px;">Pembayaran Berhasil!</h4>
                <p style="font-size:14px;color:#666;">Transaksi Anda sedang diproses. Silakan cek status pesanan di halaman riwayat.</p>
                <button type="button" class="btn btn-primary" id="btn_to_home" style="margin-top:20px;padding:10px 30px;">
                    Kembali ke Beranda
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Toggle detail
    document.getElementById('link_detail').addEventListener('click', function(e) {
        e.preventDefault();
        const detailBox = document.getElementById('detail_box');
        if (detailBox.style.display === 'none') {
            detailBox.style.display = 'block';
            this.textContent = 'Sembunyikan Detail';
        } else {
            detailBox.style.display = 'none';
            this.textContent = 'Lihat Detail';
        }
    });

    // Countdown timer
    const deadline = new Date('{{ $paymentDeadline->format("Y-m-d H:i:s") }}');
    
    function updateCountdown() {
        const now = new Date();
        const diff = deadline - now;
        
        if (diff <= 0) {
            document.getElementById('countdown_timer').textContent = '00:00:00';
            return;
        }
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        document.getElementById('countdown_timer').textContent = 
            String(hours).padStart(2, '0') + ':' + 
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0');
    }
    
    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>
@endpush

