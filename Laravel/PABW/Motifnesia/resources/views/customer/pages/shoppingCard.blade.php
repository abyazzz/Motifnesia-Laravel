@extends('customer.layouts.mainLayout')

@section('container')
<div class="shopping-cart-container" style="padding: 20px;">
    <h2>Keranjang Belanja</h2>
    <form id="cartForm">
        <table style="width:100%; border-radius: 8px; overflow: hidden;">
            <thead style="background: #D2B48C;">
                <tr>
                    <th></th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr data-id="{{ $item->id }}" data-price="{{ $item->produk->harga }}">
                    <td><input type="checkbox" class="cart-check" checked></td>
                    <td style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ asset('images/' . $item->produk->gambar) }}" alt="{{ $item->produk->nama_produk }}" style="width: 60px; border-radius: 6px;">
                        <div>
                            <div>{{ $item->produk->nama_produk }} - {{ $item->ukuran }}</div>
                        </div>
                    </td>
                    <td>Rp{{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                    <td>
                        <button type="button" class="qty-btn" onclick="updateQty({{ $item->id }}, -1)">-</button>
                        <span class="qty">{{ $item->qty }}</span>
                        <button type="button" class="qty-btn" onclick="updateQty({{ $item->id }}, 1)">+</button>
                    </td>
                    <td class="subtotal">Rp{{ number_format($item->produk->harga * $item->qty, 0, ',', '.') }}</td>
                    <td><button type="button" class="remove-btn" onclick="removeItem({{ $item->id }})">🗑️</button></td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;">Keranjang kosong.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong>Total:</strong>
                <span id="totalPrice">Rp0</span>
            </div>
            <button type="button" class="btn-checkout" id="btnCheckout">Checkout</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('btnCheckout').addEventListener('click', function() {
    // Ambil id produk yang dicentang
    const checkedRows = Array.from(document.querySelectorAll('tbody tr')).filter(row => row.querySelector('.cart-check').checked);
    const selectedIds = checkedRows.map(row => row.getAttribute('data-id'));
    if (selectedIds.length === 0) {
        alert('Pilih produk yang ingin di-checkout!');
        return;
    }
    fetch("{{ route('customer.checkout.session') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ selected_ids: selectedIds })
    })
    .then(res => res.ok ? window.location.href = "{{ route('customer.checkout.index') }}" : alert('Gagal checkout!'));
});
function formatRupiah(angka) {
    return 'Rp' + angka.toLocaleString('id-ID');
}
function updateTotal() {
    let total = 0;
    document.querySelectorAll('tbody tr').forEach(function(row) {
        const checkbox = row.querySelector('.cart-check');
        if (checkbox && checkbox.checked) {
            const subtotal = row.querySelector('.subtotal').innerText.replace(/[^\d]/g, '');
            total += parseInt(subtotal || 0);
        }
    });
    document.getElementById('totalPrice').innerText = formatRupiah(total);
}
function updateQty(id, delta) {
    const row = document.querySelector('tr[data-id="' + id + '"]');
    if (!row) return;
    let qtySpan = row.querySelector('.qty');
    let qty = parseInt(qtySpan.innerText);
    qty += delta;
    if (qty < 1) qty = 1;
    qtySpan.innerText = qty;
    const price = parseInt(row.getAttribute('data-price'));
    row.querySelector('.subtotal').innerText = formatRupiah(price * qty);
    updateTotal();
}
function removeItem(id) {
    const row = document.querySelector('tr[data-id="' + id + '"]');
    if (row) row.remove();
    updateTotal();
}
document.querySelectorAll('.cart-check').forEach(cb => cb.addEventListener('change', updateTotal));
window.onload = updateTotal;
</script>
@endpush
