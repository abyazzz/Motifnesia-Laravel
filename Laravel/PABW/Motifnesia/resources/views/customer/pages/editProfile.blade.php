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
                    <h3 class="text-lg font-bold mb-4">Alamat</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="addressLine" class="block text-sm font-semibold mb-2">Alamat Lengkap</label>
                            <textarea id="addressLine" name="address_line" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" 
                                      rows="3" 
                                      placeholder="Jalan, rt/rw, blok...">{{ old('address_line', $userProfile['address_line'] ?? '') }}</textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label for="city" class="block text-sm font-semibold mb-2">Kota</label>
                                <input id="city" name="city" value="{{ old('city', $userProfile['city'] ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" 
                                       placeholder="Kota">
                            </div>
                            <div>
                                <label for="province" class="block text-sm font-semibold mb-2">Provinsi</label>
                                <input id="province" name="province" value="{{ old('province', $userProfile['province'] ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" 
                                       placeholder="Provinsi">
                            </div>
                            <div>
                                <label for="postal" class="block text-sm font-semibold mb-2">Kode Pos</label>
                                <input id="postal" name="postal_code" value="{{ old('postal_code', $userProfile['postal_code'] ?? '') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-orange-500" 
                                       placeholder="Kode Pos">
                            </div>
                        </div>
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
    <script>
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
    </script>
@endsection