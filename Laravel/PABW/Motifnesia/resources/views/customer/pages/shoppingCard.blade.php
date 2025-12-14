@extends('customer.layouts.mainLayout')

@section('container')

<div class="min-h-screen pt-20 px-8" style="background-color: #f5f5f5;">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-6 pt-3">Keranjang Belanja</h2>

        @if($items->isEmpty())
            <div class="bg-white rounded-lg p-8 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <p class="text-gray-600 mb-4">Keranjang belanja Anda masih kosong.</p>
                <a href="{{ route('customer.home') }}" class="inline-block px-6 py-2 bg-orange-700 text-white rounded-lg hover:bg-orange-800">
                    Belanja sekarang
                </a>
            </div>
        @else

        <form action="{{ route('customer.cart.checkout') }}" method="POST">
            @csrf

            @foreach ($items as $item)
            <div class="bg-white rounded-lg p-4 mb-3 item-card" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);"
                 data-price="{{ $item->produk ? $item->produk->harga : 0 }}" 
                 data-qty="{{ $item->qty }}">
                <div class="flex items-center gap-4">

                    <!-- Checkbox -->
                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                           class="item-checkbox w-5 h-5 cursor-pointer">

                    <!-- Image -->
                    @if($item->produk)
                    <img src="{{ asset('images/'.$item->produk->gambar) }}" 
                         alt="{{ $item->produk->nama_produk }}"
                         class="w-20 h-20 object-cover rounded-lg">
                    @else
                    <div class="w-20 h-20 bg-gray-300 rounded-lg"></div>
                    @endif

                    <!-- Product Info -->
                    <div class="flex-1">
                        <div class="font-semibold text-lg">
                            {{ $item->produk->nama_produk }} - {{ $item->ukuran }}
                        </div>
                        <div class="text-gray-600 text-sm mt-1">
                            Rp {{ number_format($item->produk->harga, 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Qty Controls -->
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn-qty-minus w-8 h-8 rounded-lg border-2 border-gray-300 flex items-center justify-center hover:bg-gray-100" 
                                data-cart-id="{{ $item->id }}" data-qty="{{ $item->qty }}">−</button>
                        <input type="text" value="{{ $item->qty }}" readonly class="qty-display w-12 text-center border-2 border-gray-300 rounded-lg py-1">
                        <button type="button" class="btn-qty-plus w-8 h-8 rounded-lg border-2 border-gray-300 flex items-center justify-center hover:bg-gray-100" 
                                data-cart-id="{{ $item->id }}" data-qty="{{ $item->qty }}">+</button>
                    </div>

                    <!-- Subtotal -->
                    <div class="w-32 text-right">
                        <div class="font-bold text-lg">Rp {{ number_format($item->produk->harga * $item->qty, 0, ',', '.') }}</div>
                    </div>

                    <!-- Delete Button -->
                    <button type="button" class="btn-delete-item text-red-500 hover:text-red-700 p-2" data-cart-id="{{ $item->id }}">
                        <i class="fa fa-trash text-xl"></i>
                    </button>

                </div>
            </div>
            @endforeach

            <!-- TOTAL -->
            <div class="bg-white rounded-lg p-6 mt-6" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                <div class="flex justify-between items-center mb-4">
                    <div class="text-xl font-semibold">Total:</div>
                    <div class="text-2xl font-bold" id="total-display">Rp 0</div>
                </div>

                <button type="submit" class="w-full py-3 rounded-lg font-semibold text-white transition-colors" style="background-color: #8B4513;">
                    Checkout
                </button>
            </div>

        </form>

        @endif

    </div>
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
                parent.querySelector('.qty-display').value = qty;
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
