@extends('layouts.mainLayout')

@section('container')
    <div class="payment-confirm-container">
        <div class="payment-card">
            
            {{-- Bagian Timer dan Tanggal --}}
            <div class="expiry-header">
                <span class="label">Bayar sebelum</span>
                <span class="countdown">{{ $paymentInfo['expiry_time'] }}</span>
            </div>
            <div class="datetime">
                {{ $paymentInfo['expiry_date'] }}, {{ date('H:i') }}
            </div>

            <hr class="separator">

            {{-- Detail Tagihan --}}
            <div class="payment-details">
                <p class="method-title">Nomor metode pembayaran dengan {{ $paymentInfo['method'] }}:</p>
                <p class="payment-number">{{ $paymentInfo['payment_number'] }}</p>
                
                <p class="total-tagihan-title">Total Tagihan:</p>
                <p class="total-tagihan-amount">Rp{{ number_format($paymentInfo['total_tagihan'], 0, ',', '.') }}</p>
            </div>

            {{-- Input Nomor Metode Pembayaran --}}
            <div class="input-section">
                <p class="input-label">Masukkan nomor metode pembayaran untuk melakukan pembayaran</p>
                <input type="number" placeholder="Masukkan nomor" class="payment-input">
            </div>

            {{-- Tombol Bayar --}}
            <div class="button-section">
                <button 
                    class="btn-pay" 
                    {{-- Ini akan mengarahkan browser ke URL yang dihasilkan oleh route('home') --}}
                    onclick="window.location='{{ route('home') }}'">
                    Bayar
                </button>
            </div>
        </div>
    </div>
@endsection