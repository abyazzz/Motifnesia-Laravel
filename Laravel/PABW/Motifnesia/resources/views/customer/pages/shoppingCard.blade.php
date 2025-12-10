@extends('customer.layouts.mainLayout')

@section('container')

<div class="container mt-4">

    <h2>Keranjang Belanja</h2>

    @if($items->isEmpty())
        <div class="alert alert-info">
            Keranjang belanja Anda masih kosong. <a href="{{ route('customer.home') }}">Belanja sekarang</a>
        </div>
    @else

    <form action="{{ route('customer.cart.checkout') }}" method="POST">
        @csrf

        @foreach ($items as $item)
        <div class="card mb-3 p-3 shadow-sm item-card" style="border-radius: 12px;" 
             data-price="{{ $item->produk ? $item->produk->harga : 0 }}" 
             data-qty="{{ $item->qty }}">
            <div class="d-flex align-items-center">

                <!-- Checkbox -->
                <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                       class="me-3 item-checkbox" style="transform: scale(1.3);">

                <!-- Image -->
                @if($item->produk)
                <img src="{{ asset('images/'.$item->produk->gambar) }}" 
                     alt="{{ $item->produk->nama_produk }}"
                     style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                @else
                <div style="width:70px; height:70px; background:#ddd; border-radius:8px;"></div>
                @endif

                <div class="ms-3" style="flex:1;">
                    <!-- Nama + Ukuran -->
                    <div style="font-size:16px; font-weight:600;">
                        {{ $item->produk->nama_produk }} - {{ $item->ukuran }}
                    </div>

                    <!-- Harga -->
                    <div style="color:#666;">
                        Rp. {{ number_format($item->produk->harga, 0, ',', '.') }}
                    </div>
                </div>

                <!-- Qty Update Form -->
                <div class="d-flex align-items-center me-3">
                    <button type="button" class="btn btn-light border btn-qty-minus" data-cart-id="{{ $item->id }}" data-qty="{{ $item->qty }}">−</button>
                    <span class="mx-2 qty-display">{{ $item->qty }}</span>
                    <button type="button" class="btn btn-light border btn-qty-plus" data-cart-id="{{ $item->id }}" data-qty="{{ $item->qty }}">+</button>
                </div>

                <!-- Subtotal -->
                <div style="width:150px; text-align:right;">
                    <strong>Rp. {{ number_format($item->produk->harga * $item->qty, 0, ',', '.') }}</strong>
                </div>

                <!-- Delete Button -->
                <button type="button" class="btn btn-link text-danger btn-delete-item" data-cart-id="{{ $item->id }}">
                    <i class="fa fa-trash"></i>
                </button>

            </div>
        </div>
        @endforeach

        <!-- TOTAL -->
        <div class="card p-3 mt-4 shadow-sm" style="border-radius:12px;">
            <div style="font-size:20px; font-weight:600;">Total:</div>
            <div style="font-size:22px; font-weight:700;" id="total-display">
                Rp. 0
            </div>

            <button type="submit" class="btn btn-dark mt-3" style="padding:10px 20px; font-size:18px;">
                Checkout
            </button>
        </div>

    </form>

    @endif

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Function untuk hitung total berdasarkan checkbox yang dipilih
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            const card = checkbox.closest('.item-card');
            const price = parseInt(card.getAttribute('data-price'));
            const qty = parseInt(card.getAttribute('data-qty'));
            total += price * qty;
        });
        document.getElementById('total-display').textContent = 'Rp. ' + total.toLocaleString('id-ID');
    }

    // Event listener untuk checkbox
    document.querySelectorAll('.item-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', calculateTotal);
    });

    // Calculate total saat pertama load
    calculateTotal();
    document.querySelectorAll('.btn-qty-plus').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.getAttribute('data-cart-id');
            const currentQty = parseInt(this.getAttribute('data-qty'));
            const newQty = currentQty + 1;
            updateQty(cartId, newQty, this);
        });
    });

    // Update qty minus
    document.querySelectorAll('.btn-qty-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartId = this.getAttribute('data-cart-id');
            const currentQty = parseInt(this.getAttribute('data-qty'));
            if (currentQty > 1) {
                const newQty = currentQty - 1;
                updateQty(cartId, newQty, this);
            }
        });
    });

    // Delete item
    document.querySelectorAll('.btn-delete-item').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Hapus item dari keranjang?')) {
                const cartId = this.getAttribute('data-cart-id');
                deleteItem(cartId);
            }
        });
    });

    function updateQty(cartId, qty, btnElement) {
        fetch('{{ route("customer.cart.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                cart_id: cartId,
                qty: qty
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update tampilan qty dan data-qty di card
                const card = btnElement.closest('.item-card');
                const parent = btnElement.closest('.d-flex');
                parent.querySelector('.qty-display').textContent = qty;
                parent.querySelectorAll('button').forEach(b => {
                    b.setAttribute('data-qty', qty);
                });
                card.setAttribute('data-qty', qty);
                
                // Recalculate total tanpa reload
                calculateTotal();
            } else {
                alert(data.message || 'Gagal update qty');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }

    function deleteItem(cartId) {
        fetch('{{ route("customer.cart.delete", ":id") }}'.replace(':id', cartId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
});
</script>
@endpush
