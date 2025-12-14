@extends('customer.layouts.mainLayout')

@section('container')
<div class="min-h-screen pt-20 px-8" style="background-color: #f5f5f5;">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold mb-6 pt-3 text-center">Transaksi</h2>

        {{-- Header Bayar Sebelum --}}
        <div class="bg-yellow-50 rounded-lg p-6 mb-4 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <div class="flex items-center justify-center gap-4 mb-3">
                <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center">
                    <i class="fa fa-clock text-3xl" style="color:#8B4513;"></i>
                </div>
                <div class="text-left">
                    <h3 class="text-lg font-semibold">Bayar sebelum</h3>
                    <p id="deadline_display" class="text-gray-700">{{ $paymentDeadline->format('d F Y H:i') }} WIB</p>
                </div>
            </div>
            <div id="countdown_timer" class="text-2xl font-bold" style="color:#8B4513;">23:59:59</div>
        </div>

        {{-- Nomor Rekening & Info Payment --}}
        <div class="bg-white rounded-lg p-6 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <p class="text-sm text-gray-600 mb-4">{{ $checkoutData['created_at']->format('d F Y H:i') }} WIB</p>
            <h3 class="text-4xl font-bold tracking-wider mb-6 text-center">8887867867555700</h3>
        
            <div class="mt-6">
                <h4 class="text-lg font-semibold mb-2">Total Tagihan</h4>
                <p class="text-3xl font-bold mb-3" style="color:#8B4513;">Rp {{ number_format($checkoutData['total_bayar'], 0, ',', '.') }}</p>
                <a href="#" id="link_detail" class="text-blue-600 underline text-sm cursor-pointer">Lihat Detail</a>
            </div>
        </div>

        {{-- Detail Hidden (Toggle) --}}
        <div id="detail_box" class="bg-white rounded-lg p-6 mb-4 hidden" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h4 class="text-lg font-semibold mb-4">Rincian Belanja</h4>
        
            @foreach($checkoutData['products'] as $product)
            <div class="flex justify-between py-2 text-sm border-b">
                <span>{{ $product['nama'] }} ({{ $product['ukuran'] }}) x{{ $product['qty'] }}</span>
                <span>Rp {{ number_format($product['subtotal'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            
            <div class="flex justify-between py-2 text-sm">
                <span>Subtotal Produk:</span>
                <span>Rp {{ number_format($checkoutData['subtotal_produk'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between py-2 text-sm">
                <span>Ongkos Kirim ({{ $checkoutData['metode_pengiriman']['nama'] }}):</span>
                <span>Rp {{ number_format($checkoutData['total_ongkir'], 0, ',', '.') }}</span>
            </div>
            <hr class="my-3">
            <div class="flex justify-between py-2 text-lg font-bold">
                <span>Total:</span>
                <span>Rp {{ number_format($checkoutData['total_bayar'], 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Form Input Nomor Pembayaran --}}
        <form id="form_payment" action="{{ route('customer.payment.store') }}" method="POST" onsubmit="console.log('Form submitting...'); return true;">
            @csrf
            <div class="bg-white rounded-lg p-6 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <label for="payment_number" class="block text-sm font-semibold mb-3">Masukkan Nomor Pembayaran</label>
                <input type="text" name="payment_number" id="payment_number" placeholder="Contoh: 8887867867555700" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg" required>
            </div>

            {{-- Button Bayar --}}
            <button type="submit" class="w-full py-4 rounded-lg font-semibold text-white transition-colors" style="background-color: #8B4513;">
                Konfirmasi Pembayaran
            </button>
        </form>

    </div>
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

