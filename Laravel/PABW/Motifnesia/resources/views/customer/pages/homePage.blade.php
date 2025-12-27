@extends('customer.layouts.mainLayout')

@section('container')
        {{-- Slideshow Container: 80% Width --}}
        <div class="w-[80%] mx-auto pt-20 mb-6">
            <div class="relative h-[400px] rounded-lg overflow-hidden shadow-lg">
                <div id="homepage-carousel" class="relative w-full h-full">
                    @if(isset($slides) && $slides->count())
                        @foreach($slides as $i => $slide)
                            <div class="absolute inset-0 transition-opacity duration-500 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}" data-index="{{ $i }}">
                                <img src="{{ asset($slide->gambar) }}" alt="Slide {{ $i+1 }}" class="w-full h-full object-cover" style="object-position: center 0px;">
                            </div>
                        @endforeach
                        <button id="carousel-prev" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-10 h-10 flex items-center justify-center transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button id="carousel-next" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white rounded-full w-10 h-10 flex items-center justify-center transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <p class="text-gray-500">No slides available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar + Product Grid Container: 80% Width --}}
        <div class="w-[80%] mx-auto">
            <div class="flex relative">
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

        <script>
        (function(){
            const slides = Array.from(document.querySelectorAll('#homepage-carousel [data-index]'));
            if (!slides.length) return;
            let idx = 0;
            const show = (i) => {
                slides.forEach((s, si) => {
                    s.classList.toggle('opacity-100', si === i);
                    s.classList.toggle('opacity-0', si !== i);
                });
                idx = i;
            };
            const prev = () => show((idx - 1 + slides.length) % slides.length);
            const next = () => show((idx + 1) % slides.length);
            document.getElementById('carousel-prev')?.addEventListener('click', prev);
            document.getElementById('carousel-next')?.addEventListener('click', next);
            document.addEventListener('keydown', function(e){ 
                if(e.key === 'ArrowLeft') prev(); 
                if(e.key === 'ArrowRight') next(); 
            });
        })();
        </script>

    @endsection