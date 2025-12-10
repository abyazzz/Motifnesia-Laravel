@extends('customer.layouts.mainLayout')

@section('container')
<div class="checkout-container" style="max-width:900px;margin:40px auto;padding:20px;">
    <h2 style="font-size:24px;font-weight:600;margin-bottom:30px;">Checkout</h2>

    {{-- Alamat Pengiriman --}}
    <div class="section-box" style="background:white;border:1px solid #ddd;border-radius:12px;padding:20px;margin-bottom:20px;">
        <h3 style="font-size:18px;font-weight:600;margin-bottom:15px;">Alamat</h3>
        <div style="display:flex;align-items:center;gap:10px;">
            <input type="radio" name="alamat_radio" id="alamat_default" checked style="width:18px;height:18px;">
            <label for="alamat_default" style="flex:1;font-size:14px;color:#333;">
                {{ $defaultAddress }}
            </label>
        </div>
        <input type="hidden" id="alamat_input" value="{{ $defaultAddress }}">
    </div>

    {{-- Daftar Produk --}}
    <div class="section-box" style="background:white;border:1px solid #ddd;border-radius:12px;padding:20px;margin-bottom:20px;">
        @foreach($products as $product)
        <div class="product-item" style="display:flex;align-items:center;gap:15px;padding:15px 0;border-bottom:1px solid #eee;">
            <img src="{{ asset('images/' . $product['gambar']) }}" alt="{{ $product['nama'] }}" 
                 style="width:80px;height:80px;object-fit:cover;border-radius:8px;">
            <div style="flex:1;">
                <h4 style="font-size:16px;font-weight:500;margin-bottom:5px;">{{ $product['nama'] }} - {{ $product['ukuran'] }} - {{ $product['qty'] }}x</h4>
                <p style="font-size:14px;color:#666;margin:0;">Rp. {{ number_format($product['harga'], 0, ',', '.') }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Metode Pengiriman --}}
    <div class="section-box" style="background:white;border:1px solid #ddd;border-radius:12px;padding:20px;margin-bottom:20px;">
        <h3 style="font-size:18px;font-weight:600;margin-bottom:15px;">Metode Pengiriman</h3>
        @foreach($metodePengiriman as $pengiriman)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #eee;">
            <div style="display:flex;align-items:center;gap:10px;">
                <input type="radio" name="metode_pengiriman" value="{{ $pengiriman->id }}" 
                       data-harga="{{ $pengiriman->harga }}"
                       id="pengiriman_{{ $pengiriman->id }}" 
                       style="width:18px;height:18px;"
                       @if($loop->first) checked @endif>
                <label for="pengiriman_{{ $pengiriman->id }}" style="font-size:14px;color:#333;">
                    <strong>{{ $pengiriman->nama_pengiriman }}</strong><br>
                    <small style="color:#666;">{{ $pengiriman->deskripsi_pengiriman }}</small>
                </label>
            </div>
            <span style="font-size:14px;font-weight:500;">Rp. {{ number_format($pengiriman->harga, 0, ',', '.') }}</span>
        </div>
        @endforeach
    </div>

    {{-- Metode Pembayaran --}}
    <div class="section-box" style="background:white;border:1px solid #ddd;border-radius:12px;padding:20px;margin-bottom:20px;">
        <h3 style="font-size:18px;font-weight:600;margin-bottom:15px;">Metode Pembayaran</h3>
        @foreach($metodePembayaran as $pembayaran)
        <div style="display:flex;align-items:center;gap:10px;padding:12px 0;border-bottom:1px solid #eee;">
            <input type="radio" name="metode_pembayaran" value="{{ $pembayaran->id }}" 
                   id="pembayaran_{{ $pembayaran->id }}" 
                   style="width:18px;height:18px;"
                   @if($loop->first) checked @endif>
            <label for="pembayaran_{{ $pembayaran->id }}" style="font-size:14px;color:#333;">
                {{ $pembayaran->nama_pembayaran }}
            </label>
        </div>
        @endforeach
    </div>

    {{-- Rincian Belanja --}}
    <div class="section-box" style="background:white;border:1px solid #ddd;border-radius:12px;padding:20px;margin-bottom:20px;">
        <h3 style="font-size:18px;font-weight:600;margin-bottom:15px;">Rincian belanja</h3>
        <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:14px;">
            <span>Total harga ({{ count($products) }} barang):</span>
            <span id="subtotal_display">Rp. {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:14px;">
            <span>Total Ongkos Kirim:</span>
            <span id="ongkir_display">Rp. 0</span>
        </div>
        <hr style="margin:15px 0;border:none;border-top:1px solid #eee;">
        <div style="display:flex;justify-content:space-between;padding:8px 0;font-size:16px;font-weight:600;">
            <span>Total:</span>
            <span id="total_display">Rp. {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Button Bayar --}}
    <button id="btn_bayar" style="width:100%;background:#8B4513;color:white;border:none;border-radius:8px;padding:15px;font-size:16px;font-weight:600;cursor:pointer;">
        Bayar
    </button>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotal = {{ $subtotalProduk }};
    
    // Update total saat metode pengiriman berubah
    document.querySelectorAll('input[name="metode_pengiriman"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const ongkir = parseInt(this.getAttribute('data-harga')) || 0;
            const total = subtotal + ongkir;
            
            document.getElementById('ongkir_display').textContent = 'Rp. ' + ongkir.toLocaleString('id-ID');
            document.getElementById('total_display').textContent = 'Rp. ' + total.toLocaleString('id-ID');
        });
    });

    // Trigger change untuk set nilai awal
    const checkedPengiriman = document.querySelector('input[name="metode_pengiriman"]:checked');
    if (checkedPengiriman) {
        checkedPengiriman.dispatchEvent(new Event('change'));
    }

    // Handle tombol Bayar
    document.getElementById('btn_bayar').addEventListener('click', function() {
        const alamat = document.getElementById('alamat_input').value;
        const metodePengiriman = document.querySelector('input[name="metode_pengiriman"]:checked');
        const metodePembayaran = document.querySelector('input[name="metode_pembayaran"]:checked');

        if (!metodePengiriman || !metodePembayaran) {
            alert('Pilih metode pengiriman dan pembayaran terlebih dahulu.');
            return;
        }

        // Kirim data checkout ke server (simpan ke session)
        fetch('{{ route("customer.checkout.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                alamat: alamat,
                metode_pengiriman_id: metodePengiriman.value,
                metode_pembayaran_id: metodePembayaran.value,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || 'Terjadi kesalahan.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    });
});
</script>
@endpush
