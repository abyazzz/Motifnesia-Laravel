@extends('layouts.mainLayout')

@section('container')
    <div class="profile-page-container">
        <div class="profile-card-wrapper">
            
            {{-- KOLOM KIRI (SIDEBAR / FOTO) --}}
            <div class="profile-sidebar">
                <div class="profile-avatar-section">
                    <img src="{{ asset('images/' . $userProfile['profile_pic']) }}" alt="Foto Profil" class="profile-avatar">
                    <h2 class="profile-username">{{ $userProfile['username'] }}</h2>
                </div>

                {{-- Tombol Aksi --}}
                <div class="profile-actions">
                    <button 
                        class="btn-profile-action btn-edit"
                        onclick="window.location='{{ route('profile.edit') }}'"> 
                        Edit Profil
                    </button>
                    <button class="btn-profile-action btn-history" id="openHistoryModalBtn">Riwayat Pembelian</button>
                    <button class="btn-profile-action btn-logout"><a href="{{ asset('logout') }}">Logout</a></button>
                </div>
            </div>
            
            {{-- KOLOM KANAN (DETAIL INFORMASI) --}}
            <div class="profile-main-content">
                <div class="data-section">
                    <h3 class="section-title-underline">Biodata Diri</h3>
                    
                    <div class="data-row">
                        <span class="data-label">Nama Lengkap:</span>
                        <span class="data-value">{{ $userProfile['full_name'] }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Tanggal Lahir:</span>
                        <span class="data-value">{{ $userProfile['birth_date'] }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Jenis Kelamin:</span>
                        <span class="data-value">{{ $userProfile['gender'] }}</span>
                    </div>
                </div>

                <div class="data-section">
                    <h3 class="section-title-large">Kontak</h3>
                    
                    <div class="data-row">
                        <span class="data-label-bold">Email:</span>
                        <span class="data-value">{{ $userProfile['email'] }}</span>
                    </div>
                    <div class="data-row">
                        <span class="data-label-bold">Nomor HP:</span>
                        <span class="data-value">{{ $userProfile['phone_number'] }}</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
@endsection

@include('modals.purchaseHistoryModal')
@include('modals.reviewModal') 
@include('modals.viewReviewModal')