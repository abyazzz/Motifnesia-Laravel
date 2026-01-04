@extends('customer.layouts.mainLayout')

@section('container')

<div class="min-h-screen pt-20 px-8" style="background-color: #f5f5f5;">
    <div class="max-w-6xl mx-auto">
        <h2 class="text-3xl font-bold mb-6 pt-3">Profil Saya</h2>

        <div class="grid grid-cols-3 gap-6">
            {{-- Sidebar Profile Card --}}
            <div class="col-span-1">
                <div class="bg-white rounded-lg p-6 text-center" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    {{-- Avatar dengan ornamen batik --}}
                    <div class="relative inline-block mb-4">
                        <div class="w-40 h-40 rounded-full overflow-hidden border-4 mx-auto" style="border-color: #8B4513;">
                            <img src="{{ asset('images/' . ($userProfile['profile_pic'] ?? 'placeholder_user.jpg')) }}" 
                                 alt="Foto Profil" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>

                    <h3 class="text-xl font-bold mb-6">{{ $userProfile['full_name'] ?? $userProfile['username'] }}</h3>

                    {{-- Action Buttons --}}
                    <div class="space-y-3">
                        <a href="{{ route('customer.profile.edit') }}" 
                           class="block w-full py-3 rounded-lg font-semibold text-white transition-colors" 
                           style="background-color: #8B4513;">
                            <i class="fas fa-edit mr-2"></i>Edit Profil
                        </a>
                        <button id="openHistoryModalBtn" 
                                class="block w-full py-3 rounded-lg font-semibold bg-orange-600 text-white hover:bg-orange-700 transition-colors">
                            <i class="fas fa-shopping-bag mr-2"></i>Riwayat Pembelian
                        </button>
                        <a href="{{ route('auth.logout') }}" 
                           class="block w-full py-3 rounded-lg font-semibold bg-red-500 text-white hover:bg-red-600 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                    </div>
                </div>

                {{-- Stats Card dengan motif batik --}}
                <div class="bg-gradient-to-br from-orange-100 to-yellow-50 rounded-lg p-5 mt-4" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08); border: 2px solid #8B4513;">
                    <h4 class="font-bold mb-3 flex items-center" style="color: #8B4513;">
                        <i class="fas fa-chart-line mr-2"></i>Statistik Belanja
                    </h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">Total Pesanan:</span>
                            <span class="font-bold" style="color: #8B4513;">-</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-700">Total Belanja:</span>
                            <span class="font-bold" style="color: #8B4513;">Rp -</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="col-span-2 space-y-4">
                {{-- Biodata Diri --}}
                <div class="bg-white rounded-lg p-6" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div class="flex items-center mb-4 pb-3 border-b-2" style="border-color: #8B4513;">
                        <i class="fas fa-user text-2xl mr-3" style="color: #8B4513;"></i>
                        <h3 class="text-xl font-bold">Biodata Diri</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center py-3 border-b">
                            <div class="w-40 text-gray-600 font-medium">Nama Lengkap</div>
                            <div class="flex-1 font-semibold">{{ $userProfile['username'] ?? '-' }}</div>
                        </div>
                        <div class="flex items-center py-3 border-b">
                            <div class="w-40 text-gray-600 font-medium">Email</div>
                            <div class="flex-1 font-semibold">{{ $userProfile['email'] ?? '-' }}</div>
                        </div>
                        <div class="flex items-center py-3 border-b">
                            <div class="w-40 text-gray-600 font-medium">Tanggal Lahir</div>
                            <div class="flex-1">{{ $userProfile['birth_date'] ? \Carbon\Carbon::parse($userProfile['birth_date'])->format('d M Y') : '-' }}</div>
                        </div>
                        <div class="flex items-center py-3 border-b">
                            <div class="w-40 text-gray-600 font-medium">Jenis Kelamin</div>
                            <div class="flex-1">{{ $userProfile['gender'] === 'L' ? 'Laki-laki' : ($userProfile['gender'] === 'P' ? 'Perempuan' : '-') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Kontak --}}
                <div class="bg-white rounded-lg p-6" style="box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                    <div class="flex items-center mb-4 pb-3 border-b-2" style="border-color: #8B4513;">
                        <i class="fas fa-address-book text-2xl mr-3" style="color: #8B4513;"></i>
                        <h3 class="text-xl font-bold">Informasi Kontak</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center py-3 border-b">
                            <div class="w-40 text-gray-600 font-medium">
                                <i class="fas fa-phone mr-2 text-orange-600"></i>Nomor HP
                            </div>
                            <div class="flex-1">{{ $userProfile['phone_number'] ?? '-' }}</div>
                        </div>
                        <div class="flex items-start py-3">
                            <div class="w-40 text-gray-600 font-medium pt-1">
                                <i class="fas fa-map-marker-alt mr-2 text-orange-600"></i>Alamat
                            </div>
                            <div class="flex-1">
                                @if($addresses && $addresses->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($addresses as $address)
                                            <div class="border rounded-lg p-3 {{ $address->is_primary ? 'border-orange-500 bg-orange-50' : 'border-gray-200' }}">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
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
                                                        <div class="mt-2 font-semibold">{{ $address->recipient_name }}</div>
                                                        <div class="text-sm text-gray-600">{{ $address->phone_number }}</div>
                                                        <div class="text-sm mt-1">{{ $address->address_line }}</div>
                                                        <div class="text-sm text-gray-600">
                                                            {{ $address->city }}, {{ $address->province }} {{ $address->postal_code }}
                                                        </div>
                                                        @if($address->notes)
                                                            <div class="text-xs text-gray-500 mt-1 italic">{{ $address->notes }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">Belum ada alamat. Tambahkan di halaman Edit Profil.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('customer.modals.purchaseHistoryModal')
@include('customer.modals.reviewModal')
@include('customer.modals.viewReviewModal')