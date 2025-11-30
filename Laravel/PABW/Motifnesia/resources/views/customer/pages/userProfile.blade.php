@extends('customer.layouts.mainLayout')

@section('container')
    <style>
        .profile-container { max-width:900px; margin:28px auto; }
        .profile-card { display:flex; gap:24px; background:#fff; padding:20px; border-radius:8px; box-shadow:0 1px 6px rgba(0,0,0,0.06); }
        .profile-sidebar { width:260px; text-align:center; }
        .profile-avatar { width:160px; height:160px; object-fit:cover; border-radius:8px; border:1px solid #eee; }
        .profile-actions { margin-top:14px; display:flex; flex-direction:column; gap:8px; }
        .profile-actions a, .profile-actions button { display:block; padding:10px 12px; border-radius:6px; text-decoration:none; color:#fff; }
        .btn-edit { background:#4CAF50; }
        .btn-history { background:#2196F3; border:0; }
        .btn-logout { background:#f44336; }
        .profile-main { flex:1; }
        .section { margin-bottom:18px; }
        .section h3 { margin:0 0 10px 0; font-size:16px; border-bottom:2px solid #f0f0f0; padding-bottom:8px; }
        .data-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px dashed #f2f2f2; }
        .data-label { color:#666; }
    </style>

    <div class="profile-container">
        <div class="profile-card">

            <aside class="profile-sidebar">
                <img src="{{ asset('images/' . ($userProfile['profile_pic'] ?? 'placeholder_user.jpg')) }}" alt="Foto Profil" class="profile-avatar">
                <h2 style="margin-top:12px; font-size:18px;">{{ $userProfile['full_name'] ?? $userProfile['username'] }}</h2>
                <p style="color:#777; margin-top:6px;">{{ $userProfile['email'] ?? '' }}</p>

                <div class="profile-actions">
                    <a href="{{ route('profile.edit') }}" class="btn-edit">Edit Profil</a>
                    <button id="openHistoryModalBtn" class="btn-history">Riwayat Pembelian</button>
                    <a href="{{ route('logout') }}" class="btn-logout">Logout</a>
                </div>
            </aside>

            <main class="profile-main">
                <div class="section">
                    <h3>Biodata Diri</h3>
                    <div class="data-row">
                        <div class="data-label">Nama Lengkap</div>
                        <div>{{ $userProfile['username'] ?? '-' }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Tanggal Lahir</div>
                        <div>{{ $userProfile['birth_date'] ? \Carbon\Carbon::parse($userProfile['birth_date'])->format('d M Y') : '-' }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Jenis Kelamin</div>
                        <div>{{ $userProfile['gender'] === 'L' ? 'Laki-laki' : ($userProfile['gender'] === 'P' ? 'Perempuan' : '-') }}</div>
                    </div>
                </div>

                <div class="section">
                    <h3>Kontak</h3>
                    <div class="data-row">
                        <div class="data-label">Email</div>
                        <div>{{ $userProfile['email'] ?? '-' }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Nomor HP</div>
                        <div>{{ $userProfile['phone_number'] ?? '-' }}</div>
                    </div>
                    <div class="data-row">
                        <div class="data-label">Alamat</div>
                        <div>
                            @if(!empty($userProfile['address_line']))
                                <div>{{ $userProfile['address_line'] }}</div>
                                <div style="color:#666; font-size:13px;">{{ $userProfile['city'] ?? '' }}{{ $userProfile['city'] && $userProfile['province'] ? ', ' : '' }}{{ $userProfile['province'] ?? '' }} {{ $userProfile['postal_code'] ?? '' }}</div>
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection

@include('modals.purchaseHistoryModal')
@include('modals.reviewModal')
@include('modals.viewReviewModal')