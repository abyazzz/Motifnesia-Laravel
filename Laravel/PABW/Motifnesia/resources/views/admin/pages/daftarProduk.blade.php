@extends('admin.layouts.mainLayout')

@section('title', 'Daftar Produk')

@section('content')

{{-- @dd($products) --}}
    <h2 style="margin-bottom: 20px;">Daftar Produk</h2>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 20px;
    ">
        @foreach ($products as $produk)
            @include('admin.components.bCardProduct', [
                'nama' => $produk->nama_produk ?? $produk->name ?? 'Produk',
                'gambar' => $produk->gambar ?? $produk->image ?? null
            ])
        @endforeach
    </div>

    
@endsection
