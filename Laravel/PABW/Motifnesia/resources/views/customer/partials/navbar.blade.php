<div class="container_navbarr">
    <header class="header_container_navbar">
        <h1>Motifnesia</h1>
        <section class="header_search_bar">
            <form action="" >
                <input type="text" name="header_search_bar" id="header_search_bar">
                <button type="submit">search</button>
            </form>
        </section>
        <nav>
            <ul>
                {{-- GUNAKAN route('home') --}}
                <li><a href="{{ route('customer.home') }}">Home</a></li> 
                
                <li><a href="#">About Us</a></li>
                <li><a href="{{ route('customer.notifications.index') }}">Notif</a></li>
                
                {{-- GUNAKAN route('cart.index') untuk keranjang --}}
                <li><a href="{{ route('customer.cart.index') }}"><i class="fa-solid fa-cart-shopping"></i></a></li>
                
                {{-- GUNAKAN route('favorites.index') untuk favorit (asumsi nama route ini) --}}
                <li><a href="{{ route('customer.favorites.index') }}"><i class="fa-regular fa-heart"></i></a></li>
                
                <li>|</li>
                @php
                    $sessionUser = session('user');
                @endphp
                @if (! $sessionUser)
                    <li><a href="{{ route('auth.login') }}">Login</a></li>
                @else
                    <li>
                        <a href="{{ route('customer.profile.index') }}" style="display:inline-block;">
                            <img src="{{ asset('images/' . ($sessionUser['profile_pic'] ?? 'placeholder_user.jpg')) }}" alt="Profile" style="width:40px; height:40px; object-fit:cover; border-radius:50%; vertical-align:middle;">
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </header>
</div>