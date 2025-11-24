@extends('layouts.mainLayout')

@section('container')
    <div class="edit-profile-container">
        
        <div class="edit-profile-card">
            <h1 class="edit-profile-title">Edit Profil</h1>
            {{-- Pesan sukses / error dan validasi --}}
            @if (session('success'))
                <p style="color:green; text-align:center">{{ session('success') }}</p>
            @endif
            @if (session('error'))
                <p style="color:red; text-align:center">{{ session('error') }}</p>
            @endif
            @if ($errors->any())
                <div style="color:red; margin-bottom:12px;">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form untuk mengedit data pengguna --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="max-width:720px; margin:auto;">
                @csrf
                {{-- Nama dan Nilai Input diambil dari $userProfile --}}

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $userProfile['username']) }}" class="form-control" placeholder="Username">
                </div>

                <div class="form-group">
                    <label for="fullName">Nama Lengkap</label>
                    <input type="text" id="fullName" name="full_name" value="{{ $userProfile['full_name'] }}" class="form-control" placeholder="Nama Lengkap">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $userProfile['email'] }}" class="form-control" placeholder="Email" readonly> 
                </div>

                <div class="form-group">
                    <label for="phone">Nomor HP</label>
                    <input type="text" id="phone" name="phone_number" value="{{ old('phone_number', $userProfile['phone_number']) }}" class="form-control" placeholder="Nomor HP">
                </div>
                
                <div class="form-group">
                    <label for="birthDate">Tanggal Lahir</label>
                    <input type="date" id="birthDate" name="birth_date" value="{{ old('birth_date', (isset($userProfile['birth_date']) && $userProfile['birth_date']) ? date('Y-m-d', strtotime($userProfile['birth_date'])) : '') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="form-control">
                        <option value="" {{ old('gender', $userProfile['gender']) === null ? 'selected' : '' }}>Pilih</option>
                        <option value="L" {{ old('gender', $userProfile['gender']) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $userProfile['gender']) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- Bagian Upload Foto Profil --}}
                <div class="form-group photo-upload-group">
                    <label>Foto Profil</label>
                    <p class="photo-info">Foto Saat Ini</p>
                    <div class="current-photo-preview">
                        <img id="currentPhoto" src="{{ asset('images/' . ($userProfile['profile_pic'] ?? 'placeholder_user.jpg')) }}" alt="Foto Profil" style="max-width:150px; display:block; margin-bottom:8px;">
                    </div>
                    <div class="file-input-wrapper">
                        <input type="file" id="profilePhoto" name="profile_photo" accept="image/*" class="file-input-hidden">
                        <label for="profilePhoto" class="file-input-label">Pilih Foto</label>
                        <span class="file-name-display">No file chosen</span>
                        <div id="previewWrapper" style="margin-top:8px; display:none;"></div>
                    </div>
                </div>

                {{-- Bagian Alamat --}}
                <div class="address-section" style="margin-top:12px;">
                    <h3 class="address-title">Alamat</h3>
                    <div class="form-group">
                        <label for="addressLine">Alamat</label>
                        <textarea id="addressLine" name="address_line" class="form-control" rows="2" placeholder="Jalan, rt/rw, blok...">{{ old('address_line', $userProfile['address_line'] ?? '') }}</textarea>
                    </div>
                    <div style="display:flex; gap:8px;">
                        <div style="flex:1;">
                            <label for="city">Kota</label>
                            <input id="city" name="city" value="{{ old('city', $userProfile['city'] ?? '') }}" class="form-control" placeholder="Kota">
                        </div>
                        <div style="flex:1;">
                            <label for="province">Provinsi</label>
                            <input id="province" name="province" value="{{ old('province', $userProfile['province'] ?? '') }}" class="form-control" placeholder="Provinsi">
                        </div>
                        <div style="width:160px;">
                            <label for="postal">Kode Pos</label>
                            <input id="postal" name="postal_code" value="{{ old('postal_code', $userProfile['postal_code'] ?? '') }}" class="form-control" placeholder="Kode Pos">
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="form-actions" style="display:flex; gap:8px; margin-top:12px;">
                    <button type="submit" class="btn-save" style="background:#4CAF50; color:white; padding:10px 16px; border-radius:6px; border:0;">Simpan Perubahan</button>
                    <button type="button" class="btn-close" onclick="window.location='{{ route('profile.index') }}'" style="background:#ccc; color:#111; padding:8px 30px; border-radius:6px; border:0;">Batal</button>
                </div>

            </form>
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