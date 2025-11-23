@extends('admin.layouts.mainLayout')

@section('title', 'Kelola Produk')

@section('content')
    {{-- Link CSS spesifik untuk halaman ini --}}
    <link rel="stylesheet" href="{{ asset('css/admin/productManagement.css') }}">

    <div class="product-management-container">
        <header class="management-header">
            <h1 class="header-title">Kelola Produk</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        <main class="product-grid">
            @foreach($products as $product)
                @include('admin.components.productCardManagement', ['product' => $product])
            @endforeach
        </main>
    </div>
    
    {{-- Modal Edit Produk (hidden by default) --}}
    <div id="editProductModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span id="modalClose" class="modal-close">&times;</span>
            <h2>Edit Produk</h2>
            <form id="editProductForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="edit_product_id" name="product_id" value="">

                <label for="name">Nama Produk</label>
                <input type="text" id="edit_name" name="name">

                <label for="price">Harga</label>
                <input type="text" id="edit_price" name="price">

                <label for="material">Material</label>
                <input type="text" id="edit_material" name="material">

                <label for="process">Proses</label>
                <select id="edit_process" name="process">
                    <option value="">-- Pilih Proses --</option>
                    <option value="Press">Press</option>
                    <option value="Tulis">Tulis</option>
                </select>

                <label for="sku">SKU</label>
                <input type="text" id="edit_sku" name="sku">

                <label for="category">Kategori</label>
                <input type="text" id="edit_category" name="category">

                <label for="tags">Tags</label>
                <input type="text" id="edit_tags" name="tags">
                
                <label for="description">Deskripsi</label>
                <textarea id="edit_description" name="description" rows="3"></textarea>

                <label for="ukuran">Ukuran</label>
                <select id="edit_ukuran" name="ukuran">
                    <option value="">-- Pilih Ukuran --</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="XL">XL</option>
                </select>

                <label for="jenis_lengan">Jenis Lengan</label>
                <select id="edit_jenis_lengan" name="jenis_lengan">
                    <option value="">-- Pilih Jenis Lengan --</option>
                    <option value="Pendek">Pendek</option>
                    <option value="Panjang">Panjang</option>
                </select>

                <label for="stock">Stok</label>
                <input type="number" id="edit_stock" name="stock">

                <label for="image">Ganti Gambar</label>
                <input type="file" id="edit_image" name="image">

                <div style="margin-top:12px;">
                    <button type="button" id="saveChangesBtn">Simpan Perubahan</button>
                    <button type="button" id="deleteProductBtn">Hapus Produk</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Minimal modal styles */
        .modal { position: fixed; inset:0; background: rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; z-index:9999; }
        .modal-content { background:#fff; padding:16px; width:560px; max-width:95%; border-radius:6px; }
        .modal-close { float:right; cursor:pointer; }
        .product-grid{ display:flex; flex-wrap:wrap; gap:16px; }
        .product-management-card{ width:240px; background:#6b3f34; color:#fff; padding:12px; border-radius:8px; display:flex; gap:10px; align-items:center; }
        .product-image{ width:80px; height:100px; object-fit:cover; border-radius:6px; border:6px solid #fff; }
        .card-action{ margin-left:auto; display:flex; flex-direction:column; gap:8px; }
    </style>

    <script>
        // Basic modal + AJAX handlers
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('editProductModal');
            const modalClose = document.getElementById('modalClose');
            const editForm = document.getElementById('editProductForm');
            const saveBtn = document.getElementById('saveChangesBtn');
            const deleteBtn = document.getElementById('deleteProductBtn');

            function openModalWithProduct(product) {
                document.getElementById('edit_product_id').value = product.id;
                document.getElementById('edit_name').value = product.nama_produk || '';
                document.getElementById('edit_price').value = product.harga || '';
                document.getElementById('edit_material').value = product.material || '';
                document.getElementById('edit_process').value = product.proses || '';
                document.getElementById('edit_sku').value = product.sku || '';
                document.getElementById('edit_category').value = product.kategori || '';
                document.getElementById('edit_tags').value = product.tags || '';
                document.getElementById('edit_description').value = product.deskripsi || '';
                document.getElementById('edit_ukuran').value = product.ukuran || '';
                document.getElementById('edit_jenis_lengan').value = product.jenis_lengan || '';
                document.getElementById('edit_stock').value = product.stok || '';
                modal.style.display = 'flex';
            }

            modalClose.addEventListener('click', function () { modal.style.display = 'none'; });

            // Open modal when edit clicked
            document.querySelectorAll('.edit-button').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    const card = btn.closest('.product-management-card');
                    const product = JSON.parse(card.getAttribute('data-product'));
                    openModalWithProduct(product);
                });
            });

            // Delete via modal delete button
            deleteBtn.addEventListener('click', function () {
                const id = document.getElementById('edit_product_id').value;
                if (!confirm('Yakin hapus produk ini?')) return;
                fetch(`{{ url('admin/products') }}/${id}/delete`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        // remove card
                        const card = document.querySelector(`.product-management-card[data-product*='"id":${id}']`);
                        if (card) card.remove();
                        modal.style.display = 'none';
                        alert(res.message);
                    } else alert('Gagal menghapus');
                }).catch(()=> alert('Gagal menghapus'));
            });

            // Save changes
            saveBtn.addEventListener('click', function () {
                const id = document.getElementById('edit_product_id').value;
                const fd = new FormData(editForm);
                fetch(`{{ url('admin/products') }}/${id}/update`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: fd
                }).then(r => r.json()).then(res => {
                    if (res.success) {
                        // Optionally refresh card content or entire page
                        location.reload();
                    } else {
                        alert('Gagal menyimpan');
                    }
                }).catch(()=> alert('Gagal menyimpan'));
            });

            // Also attach delete button on each card
            document.querySelectorAll('.delete-button').forEach(btn => {
                btn.addEventListener('click', function () {
                    const id = btn.getAttribute('data-id');
                    if (!confirm('Yakin hapus produk ini?')) return;
                    fetch(`{{ url('admin/products') }}/${id}/delete`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } })
                        .then(r => r.json()).then(res => {
                            if (res.success) {
                                btn.closest('.product-management-card').remove();
                                alert(res.message);
                            } else alert('Gagal menghapus');
                        }).catch(()=> alert('Gagal menghapus'));
                });
            });
        });
    </script>
@endsection