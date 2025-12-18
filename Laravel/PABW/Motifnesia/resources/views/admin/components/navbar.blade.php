<div class="bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center justify-between px-8 py-4">
        {{-- Title --}}
        <h2 class="text-2xl font-bold bg-gradient-to-r from-amber-800 to-orange-700 bg-clip-text text-transparent">
            @yield('title', 'Admin Panel')
        </h2>

        {{-- Search & Profile --}}
        <div class="flex items-center gap-4">
            {{-- Search Box --}}
            <div class="relative">
                <input type="text" 
                       placeholder="Cari..." 
                       class="w-80 pl-10 pr-4 py-2.5 border-2 border-amber-200 rounded-full focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200 transition-all">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- Profile Avatar --}}
            <div class="relative group">
                <div class="w-11 h-11 bg-gradient-to-br from-amber-600 to-orange-600 rounded-full flex items-center justify-center cursor-pointer shadow-md hover:shadow-lg transition-all duration-200 ring-2 ring-amber-200 hover:ring-amber-400">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
