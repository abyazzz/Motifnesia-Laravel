{{-- Filter Sidebar dengan Tailwind --}}
<aside class="p-6">
    <form id="filterForm" method="GET" action="{{ route('customer.home') }}">
        {{-- Filter Kategori --}}
        <div class="mb-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">
                Filter Kategori
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                        Gender
                    </label>
                    <select id="gender" name="gender" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                        <option value="">Semua</option>
                        <option value="Pria" {{ request('gender') == 'Pria' ? 'selected' : '' }}>Pria</option>
                        <option value="Wanita" {{ request('gender') == 'Wanita' ? 'selected' : '' }}>Wanita</option>
                        <option value="Anak-anak" {{ request('gender') == 'Anak-anak' ? 'selected' : '' }}>Anak-anak</option>
                    </select>
                </div>

                <div>
                    <label for="jenis_lengan" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Lengan
                    </label>
                    <select id="jenis_lengan" name="jenis_lengan"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                        <option value="">Semua</option>
                        <option value="Panjang" {{ request('jenis_lengan') == 'Panjang' ? 'selected' : '' }}>Panjang</option>
                        <option value="Pendek" {{ request('jenis_lengan') == 'Pendek' ? 'selected' : '' }}>Pendek</option>
                    </select>
                </div>
            </div>

            <button type="submit" 
                    class="w-full mt-4 px-4 py-2.5 bg-orange-700 text-white font-medium rounded-lg hover:bg-orange-800 transition">
                Filter
            </button>
        </div>
        
        {{-- Filter Harga --}}
        <div class="mb-4">
            <h3 class="text-base font-semibold text-gray-900 mb-4">
                Filter Harga
            </h3>
            
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="radio" name="price_range" value="200000-400000" 
                           {{ request('price_range') == '200000-400000' ? 'checked' : '' }}
                           class="w-4 h-4 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm text-gray-700 group-hover:text-primary-600 transition">Rp 200.000 - Rp 400.000</span>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="radio" name="price_range" value="400000-600000"
                           {{ request('price_range') == '400000-600000' ? 'checked' : '' }}
                           class="w-4 h-4 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm text-gray-700 group-hover:text-primary-600 transition">Rp 400.000 - Rp 600.000</span>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="radio" name="price_range" value="600000-800000"
                           {{ request('price_range') == '600000-800000' ? 'checked' : '' }}
                           class="w-4 h-4 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm text-gray-700 group-hover:text-primary-600 transition">Rp 600.000 - Rp 800.000</span>
                </label>
                
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="radio" name="price_range" value="800000-999999999"
                           {{ request('price_range') == '800000-999999999' ? 'checked' : '' }}
                           class="w-4 h-4 text-primary-600 focus:ring-primary-500">
                    <span class="text-sm text-gray-700 group-hover:text-primary-600 transition">Di atas Rp 800.000</span>
                </label>
            </div>
        </div>
        
        @if(request()->hasAny(['gender', 'jenis_lengan', 'price_range'])
            <a href="{{ route('customer.home') }}" 
               class="block w-full text-center px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                Reset Filter
            </a>
        @endif
    </form>
</aside>

<script>
// Auto submit form saat price range dipilih
document.querySelectorAll('input[name="price_range"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>