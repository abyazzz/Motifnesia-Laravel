@extends('customer.layouts.mainLayout')

@section('container')

<div class="min-h-screen pt-20 px-8" style="background-color: #f5f5f5;">
    <div class="max-w-4xl mx-auto">
        <h2 class="text-3xl font-bold mb-6 pt-3">Edit Profil</h2>

        <div class="bg-white rounded-lg p-8" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
            {{-- Pesan sukses / error dan validasi --}}
            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form untuk mengedit data pengguna --}}
            <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-2 gap-6">
                    {{-- Left Column --}}
                    <div class="space-y-4">
                        <div>
                            <label for="username" class="block text-sm font-semibold mb-2">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username', $userProfile['username']) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" 
                                   placeholder="Username">
                        </div>

                        <div>
                            <label for="fullName" class="block text-sm font-semibold mb-2">Nama Lengkap</label>
                            <input type="text" id="fullName" name="full_name" value="{{ $userProfile['full_name'] }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" 
                                   placeholder="Nama Lengkap">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold mb-2">Email</label>
                            <input type="email" id="email" name="email" value="{{ $userProfile['email'] }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" 
                                   placeholder="Email" readonly> 
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold mb-2">Nomor HP</label>
                            <input type="text" id="phone" name="phone_number" value="{{ old('phone_number', $userProfile['phone_number']) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" 
                                   placeholder="Nomor HP">
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-4">
                        <div>
                            <label for="birthDate" class="block text-sm font-semibold mb-2">Tanggal Lahir</label>
                            <input type="date" id="birthDate" name="birth_date" 
                                   value="{{ old('birth_date', (isset($userProfile['birth_date']) && $userProfile['birth_date']) ? date('Y-m-d', strtotime($userProfile['birth_date'])) : '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-semibold mb-2">Jenis Kelamin</label>
                            <select id="gender" name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500">
                                <option value="" {{ old('gender', $userProfile['gender']) === null ? 'selected' : '' }}>Pilih</option>
                                <option value="L" {{ old('gender', $userProfile['gender']) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', $userProfile['gender']) === 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        {{-- Bagian Upload Foto Profil --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2">Foto Profil</label>
                            <div class="flex items-center gap-4">
                                <img id="currentPhoto" src="{{ asset('images/' . ($userProfile['profile_pic'] ?? 'placeholder_user.jpg')) }}" 
                                     alt="Foto Profil" 
                                     class="w-24 h-24 rounded-full object-cover border-2" style="border-color: #8B4513;">
                                <div class="flex-1">
                                    <input type="file" id="profilePhoto" name="profile_photo" accept="image/*" class="hidden">
                                    <label for="profilePhoto" class="inline-block px-4 py-2 rounded-lg font-semibold text-white cursor-pointer transition-colors" style="background-color: #8B4513;">
                                        Pilih Foto
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <span class="file-name-display">No file chosen</span>
                                    </p>
                                    <div id="previewWrapper" class="mt-2 hidden"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Alamat --}}
                <div class="mt-8 pt-6 border-t">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold">Daftar Alamat</h3>
                        <button type="button" id="addAddressBtn" class="px-4 py-2 rounded-lg font-semibold text-white transition-colors" style="background-color: #8B4513;">
                            <i class="fas fa-plus mr-2"></i>Tambah Alamat
                        </button>
                    </div>

                    <div id="addressesList" class="space-y-3">
                        @if($addresses && $addresses->count() > 0)
                            @foreach($addresses as $address)
                                <div class="border rounded-lg p-4 {{ $address->is_primary ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }}" data-address-id="{{ $address->id }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="mb-2">
                                                @if($address->label)
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded {{ $address->is_primary ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700' }} mr-2">
                                                        {{ $address->label }}
                                                    </span>
                                                @endif
                                                @if($address->is_primary)
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold bg-green-500 text-white rounded">
                                                        <i class="fas fa-check-circle mr-1"></i>Utama
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="font-semibold">{{ $address->recipient_name }}</div>
                                            <div class="text-sm text-gray-600">{{ $address->phone_number }}</div>
                                            <div class="text-sm mt-1">{{ $address->address_line }}</div>
                                            <div class="text-sm text-gray-600">
                                                {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                            </div>
                                            @if($address->notes)
                                                <div class="text-xs text-gray-500 mt-1 italic">{{ $address->notes }}</div>
                                            @endif
                                        </div>
                                        <div class="ml-4 flex flex-col gap-2">
                                            @if(!$address->is_primary)
                                                <button type="button" onclick="setPrimaryAddress({{ $address->id }})" class="px-3 py-1 text-xs rounded bg-blue-500 text-white hover:bg-blue-600">
                                                    Jadikan Utama
                                                </button>
                                            @endif
                                            <button type="button" onclick="editAddress({{ $address->id }})" class="px-3 py-1 text-xs rounded bg-yellow-500 text-white hover:bg-yellow-600">
                                                Edit
                                            </button>
                                            <button type="button" onclick="deleteAddress({{ $address->id }})" class="px-3 py-1 text-xs rounded bg-red-500 text-white hover:bg-red-600">
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada alamat. Klik "Tambah Alamat" untuk menambahkan.</p>
                        @endif
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-4 mt-8">
                    <button type="submit" class="flex-1 py-3 rounded-lg font-semibold text-white transition-colors" style="background-color: #8B4513;">
                        Simpan Perubahan
                    </button>
                    <button type="button" onclick="window.location='{{ route('customer.profile.index') }}'" 
                            class="flex-1 py-3 rounded-lg font-semibold bg-gray-400 text-white hover:bg-gray-500 transition-colors">
                        Batal
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah/Edit Alamat --}}
<div id="addressModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modalTitle" class="text-xl font-bold">Tambah Alamat Baru</h3>
            <button type="button" onclick="closeAddressModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form id="addressForm">
            <input type="hidden" id="addressId" name="address_id">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Label Alamat</label>
                    <input type="text" id="addressLabel" name="label" class="w-full px-4 py-2 border rounded-lg" placeholder="Rumah / Kantor / Kost">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                    <input type="text" id="recipientName" name="recipient_name" required class="w-full px-4 py-2 border rounded-lg" placeholder="Nama penerima">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold mb-2">Nomor HP <span class="text-red-500">*</span></label>
                <input type="text" id="addressPhone" name="phone_number" required class="w-full px-4 py-2 border rounded-lg" placeholder="08xxxxxxxxxx">
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                <textarea id="addressLineInput" name="address_line" required rows="3" class="w-full px-4 py-2 border rounded-lg" placeholder="Jalan, No. Rumah, RT/RW"></textarea>
            </div>

            <div class="grid grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-semibold mb-2">Kota <span class="text-red-500">*</span></label>
                    <input type="text" id="addressCity" name="city" required class="w-full px-4 py-2 border rounded-lg" placeholder="Kota">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" id="addressProvince" name="province" required class="w-full px-4 py-2 border rounded-lg" placeholder="Provinsi">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-2">Kode Pos <span class="text-red-500">*</span></label>
                    <input type="text" id="addressPostal" name="postal_code" required class="w-full px-4 py-2 border rounded-lg" placeholder="12345">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-semibold mb-2">Catatan (Opsional)</label>
                <textarea id="addressNotes" name="notes" rows="2" class="w-full px-4 py-2 border rounded-lg" placeholder="Patokan, instruksi khusus, dll"></textarea>
            </div>

            <div class="flex gap-4 mt-6">
                <button type="submit" class="flex-1 py-3 rounded-lg font-semibold text-white" style="background-color: #8B4513;">
                    Simpan Alamat
                </button>
                <button type="button" onclick="closeAddressModal()" class="flex-1 py-3 rounded-lg font-semibold bg-gray-400 text-white hover:bg-gray-500">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
        // Profile photo preview
        const fileInput = document.getElementById('profilePhoto');
        const fileNameDisplay = document.querySelector('.file-name-display');
        const previewWrapper = document.getElementById('previewWrapper');
        const currentPhoto = document.getElementById('currentPhoto');

        fileInput.addEventListener('change', function (e) {
            const file = this.files[0];
            if (!file) {
                fileNameDisplay.textContent = 'No file chosen';
                previewWrapper.style.display = 'none';
                return;
            }
            fileNameDisplay.textContent = file.name;

            // Preview
            const reader = new FileReader();
            reader.onload = function (evt) {
                previewWrapper.style.display = 'block';
                previewWrapper.innerHTML = '<img src="' + evt.target.result + '" style="max-width:150px; display:block;">';
                currentPhoto.style.display = 'none';
            }
            reader.readAsDataURL(file);
        });

        // Address Management
        const addressModal = document.getElementById('addressModal');
        const addressForm = document.getElementById('addressForm');
        const modalTitle = document.getElementById('modalTitle');
        let currentAddressId = null;

        // Open modal untuk tambah alamat
        document.getElementById('addAddressBtn').addEventListener('click', function() {
            modalTitle.textContent = 'Tambah Alamat Baru';
            addressForm.reset();
            currentAddressId = null;
            document.getElementById('addressId').value = '';
            addressModal.classList.remove('hidden');
            addressModal.classList.add('flex');
        });

        // Close modal
        function closeAddressModal() {
            addressModal.classList.add('hidden');
            addressModal.classList.remove('flex');
            addressForm.reset();
            currentAddressId = null;
        }

        // Submit form alamat (tambah/edit)
        addressForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            delete data.address_id; // Hapus field address_id dari data

            const addressId = currentAddressId || document.getElementById('addressId').value;
            const url = addressId ? 
                `/profile/addresses/${addressId}/update` : 
                '/profile/addresses';
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    closeAddressModal();
                    location.reload(); // Reload halaman untuk update list alamat
                } else {
                    alert('Error: ' + (result.message || 'Terjadi kesalahan'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan alamat');
            }
        });

        // Edit alamat
        function editAddress(addressId) {
            // Ambil data alamat dari elemen
            const addressElem = document.querySelector(`[data-address-id="${addressId}"]`);
            if (!addressElem) return;

            // Buka modal dan set title
            modalTitle.textContent = 'Edit Alamat';
            currentAddressId = addressId;
            document.getElementById('addressId').value = addressId;

            // TODO: Load data dari server atau ambil dari elemen DOM
            // Untuk sekarang, bisa load ulang atau parse dari elemen
            alert('Fitur edit akan load data alamat. Untuk demo, isi manual.');
            
            addressModal.classList.remove('hidden');
            addressModal.classList.add('flex');
        }

        // Set alamat sebagai primary
        async function setPrimaryAddress(addressId) {
            if (!confirm('Jadikan alamat ini sebagai alamat utama?')) return;

            try {
                const response = await fetch(`/profile/addresses/${addressId}/set-primary`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        }

        // Delete alamat
        async function deleteAddress(addressId) {
            if (!confirm('Hapus alamat ini?')) return;

            try {
                const response = await fetch(`/profile/addresses/${addressId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            }
        }
    </script>
@endsection