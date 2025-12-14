<style>
    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .custom-modal.show {
        display: block;
    }

    .modal-content {
        background-color: #fff;
        margin: 5% auto;
        padding: 0;
        border-radius: 8px;
        width: 80%;
        max-width: 800px;
        max-height: 80vh;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        border-bottom: 1px solid #e0e0e0;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
    }

    .close-btn {
        font-size: 28px;
        font-weight: bold;
        color: #aaa;
        cursor: pointer;
    }

    .close-btn:hover {
        color: #000;
    }

    .modal-body {
        padding: 20px;
        overflow-y: auto;
        flex: 1;
    }

    .history-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        margin-bottom: 12px;
    }

    .product-thumb-hist {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }

    .product-info-hist {
        flex: 1;
    }

    .product-name-hist {
        font-weight: 600;
        font-size: 16px;
        display: block;
        margin-bottom: 4px;
    }

    .product-details-hist {
        font-size: 13px;
        color: #666;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        margin-top: 4px;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-diproses { background: #cfe2ff; color: #084298; }
    .status-dikirim { background: #d1e7dd; color: #0f5132; }
    .status-sampai { background: #d1e7dd; color: #0a3622; font-weight: 600; }

    .btn-review {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        min-width: 120px;
    }

    .btn-ulasan {
        background: #4CAF50;
        color: white;
    }

    .btn-ulasan:hover {
        background: #45a049;
    }

    .btn-ulasan:disabled {
        background: #ccc;
        color: #666;
        cursor: not-allowed;
    }

    .btn-view-ulasan {
        background: #2196F3;
        color: white;
    }

    .btn-view-ulasan:hover {
        background: #0b7dda;
    }

    .btn-retur {
        background: #ff9800;
        color: white;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-retur:hover {
        background: #e68900;
        color: white;
    }

    .btn-retur:disabled {
        background: #ccc;
        color: #666;
        cursor: not-allowed;
    }
</style>

<div id="purchaseHistoryModal" class="custom-modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Riwayat Pembelian</h2>
            <span class="close-btn" onclick="closePurchaseHistoryModal()">&times;</span>
        </div>
        
        <div class="modal-body">
            @forelse ($purchaseHistory as $item)
                <div class="history-item">
                    <img src="{{ asset('images/' . $item['gambar']) }}" alt="{{ $item['nama'] }}" class="product-thumb-hist">
                    
                    <div class="product-info-hist">
                        <span class="product-name-hist">{{ $item['nama'] }}</span>
                        <div class="product-details-hist">
                            Ukuran: {{ $item['ukuran'] }} | Qty: {{ $item['qty'] }} | Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </div>
                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $item['status_nama'])) }}">
                            📦 {{ $item['status_nama'] }}
                        </span>
                    </div>
                    
                    {{-- Tombol sesuai status --}}
                    <div style="display: flex; flex-direction: column; gap: 8px;">
                        @if ($item['status_ulasan'] === 'beri')
                            <button class="btn-review btn-ulasan btn-open-review" 
                                    data-order-item-id="{{ $item['order_item_id'] }}"
                                    data-produk-id="{{ $item['produk_id'] }}"
                                    data-product-name="{{ $item['nama'] }}">
                                Beri Ulasan
                            </button>
                        @elseif ($item['status_ulasan'] === 'lihat')
                            <button class="btn-review btn-view-ulasan btn-open-view-review" 
                                    data-order-item-id="{{ $item['order_item_id'] }}"
                                    data-product-name="{{ $item['nama'] }}">
                                Lihat Ulasan
                            </button>
                        @else
                            <button class="btn-review btn-ulasan" disabled title="Pesanan belum sampai">
                                Beri Ulasan
                            </button>
                        @endif

                        {{-- Button Retur (hanya muncul jika status Sampai dan belum ada retur) --}}
                        @if ($item['status_nama'] === 'Sampai' && !$item['has_return'])
                            <a href="{{ route('customer.returns.create', $item['order_item_id']) }}" 
                               class="btn-review btn-retur">
                                Ajukan Retur
                            </a>
                        @elseif($item['has_return'])
                            <button class="btn-review btn-retur" disabled title="Sudah mengajukan retur">
                                Retur Diajukan
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #999; padding: 40px 0;">Belum ada riwayat pembelian</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    function closePurchaseHistoryModal() {
        document.getElementById('purchaseHistoryModal').classList.remove('show');
    }

    // Open modal on button click
    document.getElementById('openHistoryModalBtn')?.addEventListener('click', function() {
        document.getElementById('purchaseHistoryModal').classList.add('show');
    });

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('purchaseHistoryModal');
        if (event.target === modal) {
            closePurchaseHistoryModal();
        }
    });
</script>