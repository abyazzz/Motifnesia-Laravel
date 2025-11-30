<div id="purchaseHistoryModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Riwayat Pembelian</h2>
            <span class="close-btn">&times;</span>
        </div>
        
        <div class="modal-body">
            @foreach ($purchaseHistory as $item)
                <div class="history-item">
                    <img src="{{ asset('images/' . $item['gambar']) }}" alt="{{ $item['nama'] }}" class="product-thumb-hist">
                    <span class="product-name-hist">{{ $item['nama'] }}</span>
                    
                    {{-- Tombol sesuai status --}}
                    @if ($item['status_ulasan'] === 'beri')
                        <button class="btn-review btn-ulasan" data-product-id="{{ $item['id'] }}">Beri Ulasan</button>
                    @else
                        <button class="btn-review btn-view-ulasan" data-product-id="{{ $item['id'] }}">Lihat Ulasan</button>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>