@extends('customer.layouts.mainLayout')

@section('container')
<div class="checkout-container" style="max-width:700px;margin:40px auto;padding:20px;">
    <form id="checkoutForm" method="POST" action="{{ route('customer.checkout.final') }}">
        @csrf
        <h3>Alamat:</h3>
        <div class="address-box" style="margin-bottom:20px;">
            <input type="text" name="alamat" value="{{ isset($alamat) ? $alamat : '' }}" class="form-control" required>
        </div>
        <h3>Produk yang di-checkout:</h3>
        <div class="product-list" style="margin-bottom:20px;">
            @foreach(isset($products) ? $products : [] as $item)
                <div style="display:flex;align-items:center;gap:15px;margin-bottom:10px;">
                    <img src="{{ asset('images/' . $item['produk']['gambar']) }}" alt="{{ $item['produk']['nama_produk'] }}" style="width:60px;border-radius:6px;">
                    <div>
                        <div>{{ $item['produk']['nama_produk'] }} - {{ $item['ukuran'] }} x{{ $item['qty'] }}</div>
                        <div>Rp{{ number_format($item['produk']['harga'], 0, ',', '.') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
        <h3>Metode Pengiriman:</h3>
        <div class="shipping-methods" style="margin-bottom:20px;">
            @foreach(isset($metodePengiriman) ? $metodePengiriman : [] as $pengiriman)
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <label>
                        <input type="radio" name="metode_pengiriman" value="{{ $pengiriman->id }}" data-harga="{{ $pengiriman->harga }}" required>
                        <strong>{{ $pengiriman->nama_pengiriman }}</strong> - {{ $pengiriman->deskripsi_pengiriman }}
                    </label>
                    <span>Rp{{ number_format($pengiriman->harga, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>
        <h3>Metode Pembayaran:</h3>
        <div class="payment-methods" style="margin-bottom:20px;">
            @foreach(isset($metodePembayaran) ? $metodePembayaran : [] as $pembayaran)
                <div style="margin-bottom:8px;">
                    <label>
                        <input type="radio" name="metode_pembayaran" value="{{ $pembayaran->id }}" required>
                        <strong>{{ $pembayaran->nama_pembayaran }}</strong> - {{ $pembayaran->deskripsi_pembayaran }}
                    </label>
                </div>
            @endforeach
        </div>
        <h3>Rincian Belanja:</h3>
        <div class="summary-box" style="margin-bottom:20px;">
            <div>Total harga produk: <span id="totalProduk">Rp0</span></div>
            <div>Total ongkir: <span id="totalOngkir">Rp0</span></div>
            <div><strong>Total: <span id="totalFinal">Rp0</span></strong></div>
        </div>
        <input type="hidden" name="total" id="totalInput" value="0">
        <button type="submit" class="btn btn-warning" style="width:100%;font-size:1.2em;">Bayar</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
function formatRupiah(angka) {
    return 'Rp' + angka.toLocaleString('id-ID');
}
function hitungTotal() {
    let totalProduk = 0;
    @foreach(isset($products) ? $products : [] as $item)
        totalProduk += {{ $item['produk']['harga'] }} * {{ $item['qty'] }};
    @endforeach
    document.getElementById('totalProduk').innerText = formatRupiah(totalProduk);
    let ongkir = 0;
    const pengiriman = document.querySelector('input[name="metode_pengiriman"]:checked');
    if (pengiriman) {
        ongkir = parseInt(pengiriman.getAttribute('data-harga'));
    }
    document.getElementById('totalOngkir').innerText = formatRupiah(ongkir);
    document.getElementById('totalFinal').innerText = formatRupiah(totalProduk + ongkir);
    document.getElementById('totalInput').value = totalProduk + ongkir;
}
document.querySelectorAll('input[name="metode_pengiriman"]').forEach(el => {
    el.addEventListener('change', hitungTotal);
});
window.onload = hitungTotal;
</script>
@endpush