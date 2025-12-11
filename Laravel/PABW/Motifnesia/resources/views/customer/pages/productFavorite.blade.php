@extends('customer.layouts.mainLayout')

@section('container')
<div style="max-width:1200px;margin:80px auto;padding:20px;">
    {{-- Header --}}
    <div style="background:#D2691E;color:white;padding:15px;border-radius:8px 8px 0 0;text-align:center;">
        <h1 style="font-size:22px;font-weight:600;margin:0;">Favorite</h1>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div style="background:#4CAF50;color:white;padding:15px;margin-top:10px;border-radius:6px;">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div style="background:#f44336;color:white;padding:15px;margin-top:10px;border-radius:6px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Favorites List --}}
    <div style="background:white;border-radius:0 0 8px 8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);padding:20px;">
        @forelse($favorites as $favorite)
            <div style="display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #eee;padding:20px 0;">
                {{-- Product Image --}}
                <div style="display:flex;align-items:center;gap:20px;flex:1;">
                    <img src="{{ asset($favorite->produk->gambar) }}" 
                         alt="{{ $favorite->produk->nama_produk }}" 
                         style="width:100px;height:100px;object-fit:cover;border-radius:8px;">
                    
                    <div>
                        <h3 style="font-size:16px;font-weight:600;color:#333;margin-bottom:5px;">
                            {{ $favorite->produk->nama_produk }} - M
                        </h3>
                        <p style="font-size:14px;color:#D2691E;font-weight:600;">
                            Rp. {{ number_format($favorite->produk->harga, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;gap:10px;">
                    {{-- Add to Cart Button --}}
                    <a href="{{ route('customer.favorites.addToCart', $favorite->id) }}" 
                       style="background:#D2691E;color:white;border:none;padding:10px 20px;border-radius:6px;cursor:pointer;text-decoration:none;display:inline-block;">
                        🛒 Tambah Keranjang
                    </a>
                    
                    {{-- Delete Button --}}
                    <form action="{{ route('customer.favorites.destroy', $favorite->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Hapus produk dari favorite?')"
                                style="background:transparent;border:none;font-size:24px;cursor:pointer;padding:10px;">
                            🗑️
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div style="padding:40px;text-align:center;color:#999;">
                Belum ada produk favorite
            </div>
        @endforelse

        {{-- Button Perbarui Favorite --}}
        @if($favorites->count() > 0)
            <div style="text-align:center;margin-top:30px;">
                <a href="{{ route('customer.home') }}" 
                   style="background:#D2691E;color:white;padding:12px 40px;border-radius:6px;text-decoration:none;display:inline-block;font-weight:600;">
                    ❤️ Perbarui Favorite
                </a>
            </div>
        @endif
    </div>
</div>
@endsection