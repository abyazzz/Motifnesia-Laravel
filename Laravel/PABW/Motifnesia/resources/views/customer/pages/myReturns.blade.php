@extends('customer.layouts.mainLayout')

@section('container')
<link rel="stylesheet" href="{{ asset('css/myReturns.css') }}">

<div class="returns-container">
    <!-- Header -->
    <div class="returns-header">
        <h1>Retur Saya</h1>
        <p>Track status pengajuan retur produk Anda</p>
    </div>

    <!-- Returns List -->
    <div class="returns-list">
        @forelse($returns as $return)
            <div class="return-card">
                <!-- Header dengan status -->
                <div class="return-card-header">
                    <div class="return-id">
                        <strong>Retur #{{ $return->id }}</strong>
                        <span class="return-date">{{ $return->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <span class="status-badge status-{{ strtolower($return->status) }}">
                        {{ $return->status }}
                    </span>
                </div>

                <!-- Product Info -->
                <div class="product-info">
                    <img src="{{ asset('images/' . $return->produk->gambar) }}" alt="{{ $return->produk->nama_produk }}" class="product-image">
                    <div class="product-details">
                        <h3>{{ $return->produk->nama_produk }}</h3>
                        <p>Ukuran: {{ $return->orderItem->ukuran }} | Qty: {{ $return->orderItem->qty }}</p>
                        <p class="order-ref">Order ID: #{{ $return->order_id }}</p>
                    </div>
                </div>

                <!-- Return Details -->
                <div class="return-details">
                    <div class="detail-row">
                        <span class="label">Alasan:</span>
                        <span class="value">{{ $return->reason }}</span>
                    </div>
                    @if($return->description)
                        <div class="detail-row">
                            <span class="label">Keterangan:</span>
                            <span class="value">{{ $return->description }}</span>
                        </div>
                    @endif
                    <div class="detail-row">
                        <span class="label">Tipe:</span>
                        <span class="value">{{ $return->action_type }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="label">Jumlah Refund:</span>
                        <span class="value"><strong>Rp {{ number_format($return->refund_amount, 0, ',', '.') }}</strong></span>
                    </div>
                    @if($return->refund_status !== 'Belum')
                        <div class="detail-row">
                            <span class="label">Status Refund:</span>
                            <span class="value refund-{{ strtolower($return->refund_status) }}">{{ $return->refund_status }}</span>
                        </div>
                    @endif
                </div>

                <!-- Photo Proof -->
                @if($return->photo_proof)
                    <div class="photo-proof">
                        <span class="label">Foto Bukti:</span>
                        <img src="{{ asset('storage/' . $return->photo_proof) }}" alt="Bukti" class="proof-image" onclick="openImageModal(this.src)">
                    </div>
                @endif

                <!-- Admin Note -->
                @if($return->admin_note)
                    <div class="admin-note">
                        <strong>💬 Catatan Admin:</strong>
                        <p>{{ $return->admin_note }}</p>
                    </div>
                @endif

                <!-- Actions -->
                <div class="return-actions">
                    @if($return->status === 'Pending')
                        <form action="{{ route('customer.returns.cancel', $return->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-cancel" onclick="return confirm('Yakin ingin membatalkan pengajuan retur ini?')">
                                Batalkan Retur
                            </button>
                        </form>
                    @endif
                    
                    @if($return->status === 'Disetujui')
                        <div class="info-message">
                            ✅ Retur disetujui! Silakan kirim barang ke alamat yang telah diberikan admin.
                        </div>
                    @endif

                    @if($return->status === 'Selesai')
                        <div class="success-message">
                            ✅ Retur selesai diproses. Terima kasih!
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">📦</div>
                <h3>Belum Ada Retur</h3>
                <p>Anda belum pernah mengajukan retur produk.</p>
                <a href="{{ route('customer.profile.index') }}" class="btn-back">Kembali ke Profil</a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($returns->hasPages())
        <div class="pagination-container">
            {{ $returns->links() }}
        </div>
    @endif
</div>

<!-- Image Modal -->
<div id="imageModal" class="image-modal" onclick="closeImageModal()">
    <span class="close-modal">&times;</span>
    <img class="modal-image" id="modalImage">
</div>

<script>
function openImageModal(src) {
    document.getElementById('imageModal').style.display = 'flex';
    document.getElementById('modalImage').src = src;
}

function closeImageModal() {
    document.getElementById('imageModal').style.display = 'none';
}
</script>
@endsection
