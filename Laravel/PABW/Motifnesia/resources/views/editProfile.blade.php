@extends('layouts.mainLayout')

@section('container')
    <div class="edit-profile-container">
        
        <div class="edit-profile-card">
            <h1 class="edit-profile-title">Edit Profil</h1>

            {{-- Form untuk mengedit data pengguna --}}
            <form action="#" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Nama dan Nilai Input diambil dari $userProfile --}}

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="{{ $userProfile['username'] }}" class="form-control" placeholder="Username">
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
                    <input type="text" id="phone" name="phone_number" value="{{ $userProfile['phone_number'] }}" class="form-control" placeholder="Nomor HP">
                </div>
                
                <div class="form-group">
                    <label for="birthDate">Tanggal Lahir</label>
                    <input type="date" id="birthDate" name="birth_date" value="{{ date('Y-m-d', strtotime($userProfile['birth_date'])) }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="gender">Jenis Kelamin</label>
                    <input type="text" id="gender" name="gender" value="{{ $userProfile['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' }}" class="form-control" placeholder="Jenis Kelamin">
                </div>

                {{-- Bagian Upload Foto Profil --}}
                <div class="form-group photo-upload-group">
                    <label>Foto Profil</label>
                    <p class="photo-info">Foto Lama</p>
                    
                    <div class="file-input-wrapper">
                        <input type="file" id="profilePhoto" name="profile_photo" class="file-input-hidden">
                        <label for="profilePhoto" class="file-input-label">Choose File</label>
                        <span class="file-name-display">No file chosen</span>
                    </div>
                </div>

                {{-- Tombol Tambah Alamat (sesuai foto) --}}
                <div class="address-section">
                    <h3 class="address-title">Tambah Alamat Baru</h3>
                    <button type="button" class="btn-add-address">+ Tambah Alamat</button>
                </div>

                {{-- Tombol Aksi --}}
                <div class="form-actions">
                    <button type="button" class="btn-close" onclick="window.location='{{ route('profile.index') }}'">Simpan Perubahan</button>
                    <button type="button" class="btn-close" onclick="window.location='{{ route('profile.index') }}'">Close</button>
                </div>

                <h3 class="address-saved-title">Alamat Tersimpan</h3>
            </form>
        </div>
    </div>
@endsection