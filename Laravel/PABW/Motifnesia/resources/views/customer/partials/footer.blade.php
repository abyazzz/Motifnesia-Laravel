{{-- Footer Customer --}}
<footer class="bg-orange-800 text-white mt-16">
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Logo & Brand --}}
            <div class="col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 bg-white rounded flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-800" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold">Motifnesia</span>
                </div>
                <p class="text-orange-100 text-sm leading-relaxed">
                    Platform jual beli produk batik dan tenun Indonesia berkualitas tinggi dengan berbagai motif khas Nusantara.
                </p>
            </div>

            {{-- Tentang Kami --}}
            <div class="col-span-1">
                <h3 class="text-lg font-semibold mb-4">Tentang Kami</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-orange-100 hover:text-white transition text-sm">Tentang Motifnesia</a>
                    </li>
                    <li>
                        <a href="#" class="text-orange-100 hover:text-white transition text-sm">Cara Berbelanja</a>
                    </li>
                    <li>
                        <a href="#" class="text-orange-100 hover:text-white transition text-sm">Kebijakan Privasi</a>
                    </li>
                    <li>
                        <a href="#" class="text-orange-100 hover:text-white transition text-sm">Syarat & Ketentuan</a>
                    </li>
                </ul>
            </div>

            {{-- Bantuan --}}
            <div class="col-span-1">
                <h3 class="text-lg font-semibold mb-4">Bantuan</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-orange-100 hover:text-white transition text-sm">FAQ</a>
                    </li>
                    <li>
                        <a href="#" class="text-orange-100 hover:text-white transition text-sm">Cara Pembayaran</a>
                    </li>
                    <li>
                        <a href="#" class="text-orange-100 hover:text-white transition text-sm">Pengiriman</a>
                    </li>
                    <li>
                        <a href="#" class="text-orange-100 hover:text-white transition text-sm">Pengembalian & Retur</a>
                    </li>
                </ul>
            </div>

            {{-- Media Sosial & Kontak --}}
            <div class="col-span-1">
                <h3 class="text-lg font-semibold mb-4">Ikuti Kami</h3>
                <div class="space-y-3">
                    <a href="https://instagram.com/motifnesia.ig" target="_blank" 
                       class="flex items-center gap-3 text-orange-100 hover:text-white transition text-sm">
                        <i class="fab fa-instagram text-xl"></i>
                        <span>@motifnesia.ig</span>
                    </a>
                    <a href="mailto:motifnesia@gmail.com" 
                       class="flex items-center gap-3 text-orange-100 hover:text-white transition text-sm">
                        <i class="far fa-envelope text-xl"></i>
                        <span>motifnesia@gmail.com</span>
                    </a>
                    <a href="https://twitter.com/motifnesia" target="_blank" 
                       class="flex items-center gap-3 text-orange-100 hover:text-white transition text-sm">
                        <i class="fab fa-x-twitter text-xl"></i>
                        <span>@motifnesia</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="border-t border-orange-700 mt-8 pt-6">
            <p class="text-center text-orange-100 text-sm">
                &copy; {{ date('Y') }} Motifnesia. All rights reserved.
            </p>
        </div>
    </div>
</footer>
