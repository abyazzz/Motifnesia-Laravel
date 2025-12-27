{{-- Navbar dengan Tailwind - Fixed Top --}}
<div class="fixed top-0 left-0 right-0 z-50 p-2">
    <header class="w-full bg-orange-800 shadow-md rounded-[10px]">
        <div class="flex items-center justify-between h-16 px-6">
            {{-- Logo --}}
            <a href="{{ route('customer.home') }}" class="flex items-center gap-2 text-white hover:opacity-90 transition">
                <div class="w-8 h-8 bg-white rounded flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-800" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold">Motifnesia</span>
            </a>

            {{-- Search Bar --}}
            <form action="{{ route('customer.home') }}" method="GET" class="flex-1 max-w-md mx-8">
                <div class="flex items-center bg-white rounded-lg overflow-hidden">
                    <input type="text" 
                           name="search" 
                           placeholder="Cari..." 
                           value="{{ request('search') }}"
                           class="flex-1 px-4 py-2 text-sm focus:outline-none">
                    <button type="submit" 
                            class="px-6 py-2 bg-orange-700 text-white text-sm font-medium hover:bg-orange-900 transition">
                        Cari
                    </button>
                </div>
            </form>

            {{-- Navigation Menu --}}
            <nav class="flex items-center gap-6">
                <a href="{{ route('customer.home') }}" 
                   class="text-white text-sm font-medium hover:text-orange-200 transition">
                    Home
                </a>
                <a href="{{ route('customer.chat.index') }}" 
                   class="text-white text-lg hover:text-orange-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </a>
                <a href="{{ route('customer.notifications.index') }}" 
                   class="text-white text-lg hover:text-orange-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                </a>

                {{-- Icons --}}
                <a href="{{ route('customer.cart.index') }}" 
                   class="text-white text-lg hover:text-orange-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </a>
                <a href="{{ route('customer.favorites.index') }}" 
                   class="text-white text-lg hover:text-orange-200 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </a>

                {{-- Divider --}}
                <div class="h-6 w-px bg-white/30"></div>

                {{-- Profile / Login --}}
                @if (!Auth::check())
                    <a href="{{ route('auth.login') }}" 
                       class="text-white text-sm font-medium hover:text-orange-200 transition">
                        Login
                    </a>
                @else
                    <a href="{{ route('customer.profile.index') }}" 
                       class="block hover:opacity-80 transition">
                        <img src="{{ asset('images/' . (Auth::user()->profile_pic ?? 'placeholder_user.jpg')) }}" 
                             alt="Profile" 
                             class="w-9 h-9 rounded-full object-cover border-2 border-white/50">
                    </a>
                @endif
            </nav>
        </div>
    </header>
</div>