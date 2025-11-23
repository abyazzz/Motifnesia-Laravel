@extends('layouts.mainLayout')

@section('container')
    <div class="transaction-container">
        
        {{-- BAGIAN ALAMAT --}}
        <div class="section-box address-section">
            <h3>Alamat:</h3>
            <select class="form-select address-select">
                <option selected>-- Pilih Alamat --</option>
                <option value="1">{{ $transactionData['alamat'] }}</option>
            </select>
        </div>

        {{-- BAGIAN PRODUK --}}
        <div class="section-box product-item-summary">
            <h4>{{ $transactionData['item']['nama'] }}</h4>
            <div class="product-detail-inline">
                <img src="{{ asset('images/' . $transactionData['item']['gambar']) }}" alt="{{ $transactionData['item']['nama'] }}" class="product-thumb-sm">
                <div class="product-info-sm">
                    <p>Ukuran: {{ $transactionData['item']['ukuran'] }}</p>
                    <p>Jumlah: {{ $transactionData['item']['jumlah'] }}</p>
                    <p>Rp{{ number_format($transactionData['item']['harga'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- BAGIAN METODE PENGIRIMAN --}}
        <div class="section-box shipping-section">
            <h4>Metode Pengiriman</h4>
            <div class="shipping-option">
                <label>
                    <input type="radio" name="shipping" value="reguler">
                    <span>Regular (2-5 hari)</span>
                    <span class="price-right">Rp{{ number_format($transactionData['ongkir_reguler'], 0, ',', '.') }}</span>
                </label>
            </div>
            <div class="shipping-option">
                <label>
                    <input type="radio" name="shipping" value="express">
                    <span>Ekspres (1-2 hari)</span>
                    <span class="price-right">Rp{{ number_format($transactionData['ongkir_ekspres'], 0, ',', '.') }}</span>
                </label>
            </div>
            <div class="shipping-option">
                <label>
                    <input type="radio" name="shipping" value="ekonomis">
                    <span>Ekonomis (4-7 hari)</span>
                    <span class="price-right">Rp{{ number_format($transactionData['ongkir_ekonomis'], 0, ',', '.') }}</span>
                </label>
            </div>
        </div>

        {{-- BAGIAN METODE PEMBAYARAN --}}
        <div class="section-box payment-section">
            <h4>Metode Pembayaran</h4>
            <div class="payment-option">
                <label><input type="radio" name="payment" value="mandiri"> Mandiri Virtual Account</label>
            </div>
            <div class="payment-option">
                <label><input type="radio" name="payment" value="bca"> BCA Virtual Account</label>
            </div>
            <div class="payment-option">
                <label><input type="radio" name="payment" value="gopay"> GoPay</label>
            </div>
            <div class="payment-option">
                <label><input type="radio" name="payment" value="cod"> Bayar di Tempat (COD)</label>
            </div>
        </div>

        {{-- BAGIAN RINCIAN BELANJA --}}
        <div class="section-box summary-section">
            <h4>Rincian Belanja</h4>
            <div class="summary-line">
                <span>{{ $transactionData['item']['nama'] }} ({{ $transactionData['item']['ukuran'] }})</span>
                <span>Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
            </div>
            <div class="summary-line">
                <span>Total Harga:</span>
                <span>Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
            </div>
            <div class="summary-line">
                <span>Ongkos Kirim:</span>
                <span>Rp{{ number_format($ongkosKirim, 0, ',', '.') }}</span>
            </div>
            <hr>
            <div class="summary-line total-line">
                <span>Total Bayar:</span>
                <span>Rp{{ number_format($totalBayar, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- TOMBOL BAYAR --}}
        <div class="checkout-footer">
            <button 
                class="btn-pay" 
                {{-- Ini akan mengarahkan browser ke URL yang dihasilkan oleh route('payment.confirm') --}}
                onclick="window.location='{{ route('payment.confirm') }}'">
                Bayar
            </button>
        </div>

    </div>
@endsection