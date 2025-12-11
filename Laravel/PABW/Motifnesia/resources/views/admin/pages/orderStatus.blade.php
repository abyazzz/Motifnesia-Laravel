@extends('admin.layouts.mainLayout')

@section('title', 'Status Pesanan Pelanggan')

@section('content')
<div style="padding:30px;">
    <h1 style="font-size:24px;font-weight:600;margin-bottom:30px;">Status Pesanan Pelanggan</h1>

    <div style="background:white;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
        <table style="width:100%;border-collapse:collapse;">
            <thead style="background:#f5f5f5;">
                <tr>
                    <th style="padding:15px;text-align:left;font-weight:600;border-bottom:2px solid #ddd;">Nama Pelanggan</th>
                    <th style="padding:15px;text-align:left;font-weight:600;border-bottom:2px solid #ddd;">Detail Produk</th>
                    <th style="padding:15px;text-align:left;font-weight:600;border-bottom:2px solid #ddd;">Total</th>
                    <th style="padding:15px;text-align:left;font-weight:600;border-bottom:2px solid #ddd;">Alamat</th>
                    <th style="padding:15px;text-align:center;font-weight:600;border-bottom:2px solid #ddd;">Bukti Pembayaran</th>
                    <th style="padding:15px;text-align:center;font-weight:600;border-bottom:2px solid #ddd;">Status</th>
                    <th style="padding:15px;text-align:center;font-weight:600;border-bottom:2px solid #ddd;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="border-bottom:1px solid #eee;">
                    <td style="padding:15px;">{{ $order->user->full_name ?? $order->user->name }}</td>
                    <td style="padding:15px;">
                        @foreach($order->orderItems as $item)
                            <div style="margin-bottom:5px;">
                                • {{ $item->nama_produk }} 
                                @if($item->ukuran)
                                    ({{ $item->ukuran }})
                                @endif
                                 × {{ $item->qty }}
                                <br>
                                <small style="color:#666;">Rp {{ number_format($item->harga, 0, ',', '.') }}</small>
                            </div>
                        @endforeach
                    </td>
                    <td style="padding:15px;">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
                    <td style="padding:15px;">{{ $order->alamat }}</td>
                    <td style="padding:15px;text-align:center;">
                        <button onclick="alert('Bukti: {{ $order->payment_number }}')" 
                                style="background:#D2691E;color:white;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;font-size:14px;">
                            Lihat Bukti Pembayaran
                        </button>
                    </td>
                    <td style="padding:15px;text-align:center;">
                        <select class="status-dropdown" data-order-id="{{ $order->id }}" 
                                style="padding:8px 12px;border:1px solid #ddd;border-radius:6px;font-size:14px;cursor:pointer;
                                       @if($order->deliveryStatus->nama_status == 'Pending') background:#FFC107;
                                       @elseif($order->deliveryStatus->nama_status == 'Diproses') background:#2196F3;color:white;
                                       @elseif($order->deliveryStatus->nama_status == 'Dikemas') background:#9C27B0;color:white;
                                       @elseif($order->deliveryStatus->nama_status == 'Dalam Perjalanan') background:#FF9800;color:white;
                                       @elseif($order->deliveryStatus->nama_status == 'Sampai') background:#4CAF50;color:white;
                                       @endif">
                            @foreach($deliveryStatuses as $status)
                                <option value="{{ $status->id }}" 
                                        @if($order->delivery_status_id == $status->id) selected @endif>
                                    {{ $status->nama_status }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td style="padding:15px;text-align:center;">
                        <button onclick="updateStatus({{ $order->id }})" 
                                style="background:#8B4513;color:white;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;font-size:14px;">
                            Update
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:30px;text-align:center;color:#999;">
                        Belum ada pesanan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function updateStatus(orderId) {
    const dropdown = document.querySelector(`.status-dropdown[data-order-id="${orderId}"]`);
    const statusId = dropdown.value;
    
    fetch(`/admin/order-status/${orderId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            delivery_status_id: statusId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status berhasil diperbarui!');
            location.reload();
        } else {
            alert('Gagal memperbarui status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan.');
    });
}
</script>
@endsection