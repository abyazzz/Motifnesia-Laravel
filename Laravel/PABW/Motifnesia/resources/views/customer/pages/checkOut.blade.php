@extends('customer.layouts.mainLayout')

@section('container')
<div class="min-h-screen pt-20 px-8" style="background-color: #f5f5f5;">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold mb-6 pt-3">Checkout</h2>

        {{-- Alamat Pengiriman --}}
        <div class="bg-white rounded-lg p-6 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h3 class="text-xl font-semibold mb-4">Alamat Pengiriman</h3>
            <div class="flex items-start gap-3">
                <input type="radio" name="alamat_radio" id="alamat_default" checked class="w-5 h-5 mt-1">
                <label for="alamat_default" class="flex-1 text-gray-700">
                    {{ $defaultAddress }}
                </label>
            </div>
            <input type="hidden" id="alamat_input" value="{{ $defaultAddress }}">
        </div>

        {{-- Daftar Produk --}}
        <div class="bg-white rounded-lg p-6 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h3 class="text-xl font-semibold mb-4">Produk</h3>
            @foreach($products as $product)
            <div class="flex items-center gap-4 py-4 border-b last:border-b-0">
                <img src="{{ asset('images/' . $product['gambar']) }}" alt="{{ $product['nama'] }}" 
                     class="w-20 h-20 object-cover rounded-lg">
                <div class="flex-1">
                    <h4 class="font-semibold mb-1">{{ $product['nama'] }} - {{ $product['ukuran'] }}</h4>
                    <p class="text-sm text-gray-600">{{ $product['qty'] }}x - Rp {{ number_format($product['harga'], 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Metode Pengiriman --}}
        <div class="bg-white rounded-lg p-6 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h3 class="text-xl font-semibold mb-4">Metode Pengiriman</h3>
            @foreach($metodePengiriman as $pengiriman)
            <div class="flex items-center justify-between py-3 border-b last:border-b-0">
                <div class="flex items-start gap-3">
                    <input type="radio" name="metode_pengiriman" value="{{ $pengiriman->id }}" 
                           data-harga="{{ $pengiriman->harga }}"
                           id="pengiriman_{{ $pengiriman->id }}" 
                           class="w-5 h-5 mt-1"
                           @if($loop->first) checked @endif>
                    <label for="pengiriman_{{ $pengiriman->id }}" class="text-sm">
                        <strong class="block">{{ $pengiriman->nama_pengiriman }}</strong>
                        <span class="text-gray-600">{{ $pengiriman->deskripsi_pengiriman }}</span>
                    </label>
                </div>
                <span class="font-semibold">Rp {{ number_format($pengiriman->harga, 0, ',', '.') }}</span>
            </div>
            @endforeach
        </div>

        {{-- Metode Pembayaran --}}
        <div class="bg-white rounded-lg p-6 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h3 class="text-xl font-semibold mb-4">Metode Pembayaran</h3>
            @foreach($metodePembayaran as $pembayaran)
            <div class="flex items-center gap-3 py-3 border-b last:border-b-0">
                <input type="radio" name="metode_pembayaran" value="{{ $pembayaran->id }}" 
                       id="pembayaran_{{ $pembayaran->id }}" 
                       class="w-5 h-5"
                       @if($loop->first) checked @endif>
                <label for="pembayaran_{{ $pembayaran->id }}" class="text-sm">
                    {{ $pembayaran->nama_pembayaran }}
                </label>
            </div>
            @endforeach
        </div>

        {{-- Rincian Belanja --}}
        <div class="bg-white rounded-lg p-6 mb-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            <h3 class="text-xl font-semibold mb-4">Rincian Belanja</h3>
            <div class="flex justify-between py-2 text-sm">
                <span>Total harga ({{ count($products) }} barang):</span>
                <span id="subtotal_display">Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between py-2 text-sm">
                <span>Total Ongkos Kirim:</span>
                <span id="ongkir_display">Rp 0</span>
            </div>
            <hr class="my-4">
            <div class="flex justify-between py-2 text-lg font-bold">
                <span>Total:</span>
                <span id="total_display">Rp {{ number_format($subtotalProduk, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Button Bayar --}}
        <button id="btn_bayar" class="w-full py-4 rounded-lg font-semibold text-white transition-colors" style="background-color: #8B4513;">
            Bayar
        </button>
    </div>
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
