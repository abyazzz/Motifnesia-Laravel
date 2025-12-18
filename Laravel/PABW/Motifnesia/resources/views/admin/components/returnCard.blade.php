<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <!-- Card Header -->
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-6 py-4 flex items-center justify-between border-b border-gray-200">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">Retur #{{ $return->id }}</h3>
            <span class="text-sm text-gray-600">{{ $return->created_at->format('d M Y, H:i') }}</span>
        </div>
        @php
            $statusColors = [
                'pending' => 'bg-yellow-100 text-yellow-800',
                'disetujui' => 'bg-green-100 text-green-800',
                'ditolak' => 'bg-red-100 text-red-800',
                'diproses' => 'bg-blue-100 text-blue-800',
                'selesai' => 'bg-gray-100 text-gray-800',
            ];
            $statusColor = $statusColors[strtolower($return->status)] ?? 'bg-gray-100 text-gray-800';
        @endphp
        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColor }}">
            {{ $return->status }}
        </span>
    </div>

    <div class="p-6">
        <!-- Customer & Order Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4 text-sm">
            <div>
                <span class="text-gray-600">Customer:</span>
                <span class="font-semibold text-gray-800">{{ $return->user->name }}</span>
                <span class="text-gray-500">({{ $return->user->email }})</span>
            </div>
            <div>
                <span class="text-gray-600">Order ID:</span>
                <span class="font-semibold text-gray-800">#{{ $return->order_id }}</span>
            </div>
        </div>

        <!-- Product Info -->
        <div class="flex gap-4 p-4 bg-gray-50 rounded-lg mb-4">
            <img src="{{ asset('images/' . $return->produk->gambar) }}" 
                 alt="{{ $return->produk->nama_produk }}" 
                 class="w-20 h-20 object-cover rounded-lg border-2 border-gray-200">
            <div class="flex-1">
                <h4 class="font-semibold text-gray-800 mb-1">{{ $return->produk->nama_produk }}</h4>
                <p class="text-sm text-gray-600">Ukuran: {{ $return->orderItem->ukuran }} | Qty: {{ $return->orderItem->qty }}</p>
                <p class="text-sm font-semibold text-gray-800 mt-1">Harga: Rp {{ number_format($return->orderItem->harga_satuan, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Return Details -->
        <div class="space-y-2 text-sm mb-4">
            <div class="flex gap-2">
                <span class="text-gray-600 min-w-[140px]">Alasan:</span>
                <span class="text-gray-800">{{ $return->reason }}</span>
            </div>
            @if($return->description)
                <div class="flex gap-2">
                    <span class="text-gray-600 min-w-[140px]">Keterangan:</span>
                    <span class="text-gray-800">{{ $return->description }}</span>
                </div>
            @endif
            <div class="flex gap-2">
                <span class="text-gray-600 min-w-[140px]">Tipe Pengembalian:</span>
                <span class="font-semibold text-gray-800">{{ $return->action_type }}</span>
            </div>
            <div class="flex gap-2">
                <span class="text-gray-600 min-w-[140px]">Jumlah Refund:</span>
                <span class="font-bold text-amber-700">Rp {{ number_format($return->refund_amount, 0, ',', '.') }}</span>
            </div>
            @if($return->photo_proof)
                <div class="flex gap-2">
                    <span class="text-gray-600 min-w-[140px]">Foto Bukti:</span>
                    <a href="{{ asset('storage/' . $return->photo_proof) }}" target="_blank" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Lihat Foto →
                    </a>
                </div>
            @endif
        </div>

        <!-- Admin Note (if exists) -->
        @if($return->admin_note)
            <div class="bg-blue-50 border-l-4 border-blue-500 p-3 mb-4">
                <p class="font-semibold text-gray-800 mb-1">💬 Catatan Admin:</p>
                <p class="text-sm text-gray-700">{{ $return->admin_note }}</p>
            </div>
        @endif

        <!-- Actions -->
        <div class="flex flex-wrap gap-2 pt-4 border-t border-gray-200">
            @if($return->status === 'Pending')
                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm"
                        onclick="updateStatus({{ $return->id }}, 'Disetujui')">
                    ✓ Setujui
                </button>
                <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors text-sm"
                        onclick="promptReject({{ $return->id }})">
                    ✕ Tolak
                </button>
            @elseif($return->status === 'Disetujui')
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm"
                        onclick="updateStatus({{ $return->id }}, 'Diproses')">
                    🔄 Proses Refund
                </button>
            @elseif($return->status === 'Diproses')
                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors text-sm"
                        onclick="updateStatus({{ $return->id }}, 'Selesai')">
                    ✓ Selesaikan
                </button>
            @endif

            <form action="{{ route('admin.returns.destroy', $return->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors text-sm"
                        onclick="return confirm('Yakin ingin menghapus data retur ini?')">
                    🗑️ Hapus
                </button>
            </form>
        </div>
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
