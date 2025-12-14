@extends('customer.layouts.mainLayout')

@section('container')
        <div class="slideshow-wrapper">
            <div id="homepage-carousel" class="homepage-carousel">
                @if(isset($slides) && $slides->count())
                    @foreach($slides as $i => $slide)
                        <div class="carousel-slide" data-index="{{ $i }}" style="display: {{ $i === 0 ? 'block' : 'none' }};">
                            <img src="{{ asset($slide->gambar) }}" alt="Slide {{ $i+1 }}">
                            {{-- caption dihilangkan --}}
                        </div>
                    @endforeach
                    <button id="carousel-prev" class="carousel-control prev">‹</button>
                    <button id="carousel-next" class="carousel-control next">›</button>
                @else
                    <div class="carousel-slide" style="display:block;">
                        <div class="no-slide">No slides available</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Main Container: 80% Width, Centered, Fixed Sidebar + Scrollable Product Grid --}}
        <div class="w-[80%] mx-auto flex pt-20 relative">
            {{-- Fixed Sidebar Kiri (dalam container 80%) --}}
            <div class="sticky top-20 h-fit max-h-[calc(100vh-6rem)] w-64 shrink-0 overflow-y-auto bg-white rounded-lg" style="box-shadow: 0 0 10px 0 rgba(0,0,0,0.1);">
                @include('customer.components.sideBar')
            </div>
            
            {{-- Scrollable Product Grid Kanan --}}
            <div class="flex-1 ml-6">
                <div class="grid grid-cols-4 gap-5">
                        @foreach ($products as $product)
                            @include('customer.components.product-card', ['product' => $product])
                        @endforeach
                    </div>
                    
                    @if($products->isEmpty())
                        <div class="text-center py-20">
                            <div class="text-6xl mb-4">🔍</div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                            <p class="text-gray-600">Coba ubah filter atau reset pencarian</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <style>
        .slideshow-wrapper { max-width: 100%; margin: 0 auto 24px; position: relative; }
        .homepage-carousel { position: relative; overflow: hidden; }
        .carousel-slide { width: 100%; text-align: center; }
        .carousel-slide img { width: 100%; max-height: 420px; object-fit: cover; display: block; margin: 0 auto; }
        .carousel-caption { position: absolute; left: 20px; bottom: 20px; color: #fff; text-shadow: 0 1px 4px rgba(0,0,0,.6); }
        .carousel-control { position: absolute; top: 50%; transform: translateY(-50%); background: rgba(0,0,0,0.4); color: #fff; border: none; padding: 8px 12px; cursor: pointer; font-size: 28px; }
        .carousel-control.prev { left: 8px; }
        .carousel-control.next { right: 8px; }
        .no-slide { padding: 40px; background:#f5f5f5; }
        </style>

        <script>
        (function(){
            const slides = Array.from(document.querySelectorAll('#homepage-carousel .carousel-slide'));
            if (!slides.length) return;
            let idx = 0;
            const show = (i) => {
                slides.forEach((s, si) => s.style.display = (si === i) ? 'block' : 'none');
                idx = i;
            };
            const prev = () => show((idx - 1 + slides.length) % slides.length);
            const next = () => show((idx + 1) % slides.length);
            document.getElementById('carousel-prev')?.addEventListener('click', prev);
            document.getElementById('carousel-next')?.addEventListener('click', next);
            // optional: keyboard navigation
            document.addEventListener('keydown', function(e){ if(e.key === 'ArrowLeft') prev(); if(e.key === 'ArrowRight') next(); });
        })();
        </script>

    @endsection