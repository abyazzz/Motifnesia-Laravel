@props(['customer', 'isOdd'])

{{-- isOdd digunakan untuk memberi warna selang-seling --}}
<div class="customer-card {{ $isOdd ? 'odd-row' : 'even-row' }}">
    {{-- Kolom Nama --}}
    <div class="card-column name-col">
        <span class="user-icon">👤</span> {{-- Menggantikan icon user di foto --}}
        <span class="customer-name">{{ $customer['name'] }}</span>
    </div>

    {{-- Kolom Email --}}
    <div class="card-column email-col">
        <span class="customer-email">{{ $customer['email'] }}</span>
    </div>
</div>