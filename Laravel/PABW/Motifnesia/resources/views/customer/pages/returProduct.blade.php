@extends('customer.layouts.mainLayout')

@section('container')
<link rel="stylesheet" href="{{ asset('css/returProduct.css') }}">

<div class="return-container">
    <div class="return-card">
        <!-- Header -->
        <div class="return-header">
            <h1>Ajukan Retur Produk</h1>
            <p>Mohon lengkapi formulir di bawah ini untuk mengajukan pengembalian barang</p>
        </div>

        <!-- Product Info -->
        <div class="product-summary">
            <h3>📦 Informasi Produk</h3>
            <div class="product-item">
                <img src="{{ asset('images/' . $orderItem->produk->gambar) }}" alt="{{ $orderItem->produk->nama_produk }}" class="product-image">
                <div class="product-details">
                    <h4>{{ $orderItem->produk->nama_produk }}</h4>
                    <p>Ukuran: {{ $orderItem->ukuran }} | Qty: {{ $orderItem->qty }}</p>
                    <p>Harga: Rp {{ number_format($orderItem->harga_satuan, 0, ',', '.') }}</p>
                    <p class="order-id">Order ID: #{{ $order->id }}</p>
                </div>
            </div>
        </div>

        <!-- Return Form -->
        <form action="{{ route('customer.returns.store') }}" method="POST" enctype="multipart/form-data" class="return-form">
            @csrf
            <input type="hidden" name="order_item_id" value="{{ $orderItem->id }}">

            <!-- Reason -->
            <div class="form-group">
                <label for="reason">Alasan Retur <span class="required">*</span></label>
                <select name="reason" id="reason" class="form-control" required>
                    <option value="">-- Pilih Alasan --</option>
                    <option value="Ukuran tidak sesuai">Ukuran tidak sesuai</option>
                    <option value="Barang rusak/cacat">Barang rusak/cacat</option>
                    <option value="Salah kirim produk">Salah kirim produk</option>
                    <option value="Tidak sesuai deskripsi">Tidak sesuai deskripsi</option>
                    <option value="Berubah pikiran">Berubah pikiran</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
                @error('reason')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Description -->
            <div class="form-group">
                <label for="description">Keterangan Tambahan</label>
                <textarea name="description" id="description" rows="4" class="form-control" placeholder="Jelaskan detail masalah yang Anda alami...">{{ old('description') }}</textarea>
                <small class="form-hint">Maksimal 1000 karakter</small>
                @error('description')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Photo Proof -->
            <div class="form-group">
                <label for="photo_proof">Foto Bukti</label>
                <div class="file-upload-wrapper">
                    <input type="file" name="photo_proof" id="photo_proof" accept="image/jpeg,image/png,image/jpg" class="file-input">
                    <label for="photo_proof" class="file-label">
                        <span class="file-icon">📷</span>
                        <span class="file-text">Pilih foto (opsional)</span>
                    </label>
                    <div id="preview-container" style="display: none; margin-top: 10px;">
                        <img id="image-preview" src="" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                    </div>
                </div>
                <small class="form-hint">Format: JPG, PNG. Maksimal 2MB. Upload foto jika barang rusak/cacat.</small>
                @error('photo_proof')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Action Type -->
            <div class="form-group">
                <label>Tipe Pengembalian <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="action_type" value="Refund" checked required>
                        <span class="radio-label">
                            <strong>💰 Refund (Pengembalian Uang)</strong>
                            <small>Dana akan dikembalikan ke rekening Anda</small>
                        </span>
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="action_type" value="Tukar Barang" required>
                        <span class="radio-label">
                            <strong>🔄 Tukar Barang</strong>
                            <small>Tukar dengan produk yang sama (size/warna berbeda)</small>
                        </span>
                    </label>
                </div>
                @error('action_type')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <h4>ℹ️ Ketentuan Retur:</h4>
                <ul>
                    <li>Retur hanya dapat dilakukan maksimal <strong>7 hari</strong> sejak barang diterima</li>
                    <li>Barang harus dalam kondisi <strong>belum dipakai</strong> dan masih ada tag/label</li>
                    <li>Proses verifikasi memakan waktu <strong>1-3 hari kerja</strong></li>
                    <li>Jika disetujui, refund akan diproses dalam <strong>5-7 hari kerja</strong></li>
                    <li>Ongkir retur ditanggung customer (kecuali kesalahan toko)</li>
                </ul>
            </div>

            <!-- Buttons -->
            <div class="form-actions">
                <button type="button" onclick="window.history.back()" class="btn-cancel">Batal</button>
                <button type="submit" class="btn-submit">Ajukan Retur</button>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview
document.getElementById('photo_proof').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('image-preview').src = e.target.result;
            document.getElementById('preview-container').style.display = 'block';
        }
        reader.readAsDataURL(file);
        document.querySelector('.file-text').textContent = file.name;
    }
});
</script>
@endsection
