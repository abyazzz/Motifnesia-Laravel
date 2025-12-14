<div class="return-card">
    <!-- Card Header -->
    <div class="return-card-header">
        <div class="return-info">
            <h3>Retur #{{ $return->id }}</h3>
            <span class="return-date">{{ $return->created_at->format('d M Y, H:i') }}</span>
        </div>
        <span class="status-badge status-{{ strtolower($return->status) }}">
            {{ $return->status }}
        </span>
    </div>

    <!-- Customer & Order Info -->
    <div class="info-section">
        <div class="info-row">
            <span class="label">Customer:</span>
            <span class="value"><strong>{{ $return->user->name }}</strong> ({{ $return->user->email }})</span>
        </div>
        <div class="info-row">
            <span class="label">Order ID:</span>
            <span class="value">#{{ $return->order_id }}</span>
        </div>
    </div>

    <!-- Product Info -->
    <div class="product-section">
        <img src="{{ asset('images/' . $return->produk->gambar) }}" alt="{{ $return->produk->nama_produk }}" class="product-image">
        <div class="product-details">
            <h4>{{ $return->produk->nama_produk }}</h4>
            <p>Ukuran: {{ $return->orderItem->ukuran }} | Qty: {{ $return->orderItem->qty }}</p>
            <p class="price">Harga: Rp {{ number_format($return->orderItem->harga_satuan, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Return Details -->
    <div class="return-details">
        <div class="detail-item">
            <span class="label">Alasan:</span>
            <span class="value">{{ $return->reason }}</span>
        </div>
        @if($return->description)
            <div class="detail-item">
                <span class="label">Keterangan:</span>
                <span class="value">{{ $return->description }}</span>
            </div>
        @endif
        <div class="detail-item">
            <span class="label">Tipe Pengembalian:</span>
            <span class="value"><strong>{{ $return->action_type }}</strong></span>
        </div>
        <div class="detail-item">
            <span class="label">Jumlah Refund:</span>
            <span class="value refund-amount">Rp {{ number_format($return->refund_amount, 0, ',', '.') }}</span>
        </div>
        @if($return->photo_proof)
            <div class="detail-item">
                <span class="label">Foto Bukti:</span>
                <a href="{{ asset('storage/' . $return->photo_proof) }}" target="_blank" class="view-photo">
                    Lihat Foto →
                </a>
            </div>
        @endif
    </div>

    <!-- Admin Note (if exists) -->
    @if($return->admin_note)
        <div class="admin-note-display">
            <strong>💬 Catatan Admin:</strong>
            <p>{{ $return->admin_note }}</p>
        </div>
    @endif

    <!-- Actions -->
    <div class="return-actions">
        @if($return->status === 'Pending')
            <!-- Approve Button -->
            <button class="btn-action btn-approve" onclick="updateStatus({{ $return->id }}, 'Disetujui')">
                ✓ Setujui
            </button>
            
            <!-- Reject Button -->
            <button class="btn-action btn-reject" onclick="promptReject({{ $return->id }})">
                ✕ Tolak
            </button>
        @elseif($return->status === 'Disetujui')
            <!-- Mark as Processing -->
            <button class="btn-action btn-process" onclick="updateStatus({{ $return->id }}, 'Diproses')">
                🔄 Proses Refund
            </button>
        @elseif($return->status === 'Diproses')
            <!-- Mark as Complete -->
            <button class="btn-action btn-complete" onclick="updateStatus({{ $return->id }}, 'Selesai')">
                ✓ Selesaikan
            </button>
        @endif

        <!-- Delete Button (for any status) -->
        <form action="{{ route('admin.returns.destroy', $return->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus data retur ini?')">
                🗑️ Hapus
            </button>
        </form>
    </div>
</div>

<script>
function updateStatus(returnId, status) {
    let adminNote = '';
    
    if (status === 'Ditolak') {
        adminNote = prompt('Alasan penolakan (opsional):');
        if (adminNote === null) return; // User cancelled
    }

    fetch(`/admin/returns/${returnId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: status,
            admin_note: adminNote
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}

function promptReject(returnId) {
    const reason = prompt('Alasan penolakan:');
    if (reason === null) return;
    
    if (!reason.trim()) {
        alert('Alasan penolakan harus diisi!');
        return;
    }

    fetch(`/admin/returns/${returnId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: 'Ditolak',
            admin_note: reason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan', 'error');
    });
}
</script>
