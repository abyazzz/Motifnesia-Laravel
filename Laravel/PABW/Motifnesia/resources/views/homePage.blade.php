@extends('layouts.mainLayout')

@section('container')
    <div class="slideshow-wrapper">
        <div class="slideshow">
            Slideshow Area 
        </div>
    </div>
    <div class="page-container">
        
        @include('components.sideBar') 
        <div class="product-grid">
            @foreach ($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
@endsection