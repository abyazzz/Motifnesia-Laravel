@extends('admin.layouts.mainLayout')

@section('title', 'Kelola Produk')

@section('content')
    <div class="p-6">
        {{-- Success Alert --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Search & Action Bar --}}
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <div class="flex items-center gap-4">
                    <div class="relative flex-1">
                        <input type="text" 
                               id="searchProduct"
                               placeholder="Cari produk berdasarkan nama..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <a href="{{ route('admin.products.create') }}" 
                       class="flex items-center gap-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-semibold px-6 py-2.5 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Produk
                    </a>
                </div>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                @include('admin.components.productCardManagement', ['product' => $product])
            @endforeach
        </div>

        @if($products->isEmpty())
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Produk</h3>
                <p class="text-gray-600 mb-4">Mulai tambahkan produk batik Anda sekarang!</p>
                <a href="{{ route('admin.products.create') }}" 
                   class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Produk Pertama
                </a>
            </div>
        @endif
    </div>
    
    {{-- Modal Edit Produk --}}
    <div id="editProductModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Produk
                </h2>
                <button id="modalClose" class="text-white hover:bg-white hover:bg-opacity-20 rounded-full p-2 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-180px)]">
                <form id="editProductForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="edit_product_id" name="product_id">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama Produk --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                            <input type="text" id="edit_name" name="name" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
                            <input type="text" id="edit_price" name="price" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>

                        {{-- Stok --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Stok</label>
                            <input type="number" id="edit_stock" name="stock" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>

                        {{-- Material --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Material</label>
                            <input type="text" id="edit_material" name="material" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>

                        {{-- Proses --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Proses</label>
                            <select id="edit_process" name="process" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">-- Pilih Proses --</option>
                                <option value="Press">Press</option>
                                <option value="Tulis">Tulis</option>
                            </select>
                        </div>

                        {{-- SKU --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">SKU</label>
                            <input type="text" id="edit_sku" name="sku" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                            <input type="text" id="edit_category" name="category" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>

                        {{-- Ukuran --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ukuran</label>
                            <select id="edit_ukuran" name="ukuran" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">-- Pilih Ukuran --</option>
                                <option value="S">S</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                            </select>
                        </div>

                        {{-- Jenis Lengan --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Lengan</label>
                            <select id="edit_jenis_lengan" name="jenis_lengan" 
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <option value="">-- Pilih Jenis Lengan --</option>
                                <option value="Pendek">Pendek</option>
                                <option value="Panjang">Panjang</option>
                            </select>
                        </div>

                        {{-- Tags --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tags</label>
                            <input type="text" id="edit_tags" name="tags" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>

                        {{-- Deskripsi --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                            <textarea id="edit_description" name="description" rows="3" 
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                        </div>

                        {{-- Gambar --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ganti Gambar</label>
                            <input type="file" id="edit_image" name="image" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        </div>
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between gap-3 border-t border-gray-200">
                <button type="button" id="deleteProductBtn" 
                        class="flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Produk
                </button>
                <button type="button" id="saveChangesBtn" 
                        class="flex items-center gap-2 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-semibold px-6 py-2.5 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

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
                    const card = btn.closest('.product-card-item');
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
                        const card = document.querySelector(`.product-card-item[data-product*='"id":${id}']`);
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
                                btn.closest('.product-card-item').remove();
                                alert(res.message);
                            } else alert('Gagal menghapus');
                        }).catch(()=> alert('Gagal menghapus'));
                });
            });

            // Search functionality
            const searchInput = document.getElementById('searchProduct');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    document.querySelectorAll('.product-card-item').forEach(card => {
                        const product = JSON.parse(card.getAttribute('data-product'));
                        const namaProduk = (product.nama_produk || '').toLowerCase();
                        const harga = (product.harga || 0).toString();
                        
                        // Search by nama_produk atau harga
                        if (namaProduk.includes(searchTerm) || harga.includes(searchTerm)) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>
@endsection