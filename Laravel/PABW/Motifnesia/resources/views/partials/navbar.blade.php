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
                <li><a href="{{ route('home') }}">Home</a></li> 
                
                <li><a href="#">About Us</a></li>
                <li><a href="{{ route('notifications.index') }}">Notif</a></li>
                
                {{-- GUNAKAN route('cart.index') untuk keranjang --}}
                <li><a href="{{ route('cart.index') }}"><i class="fa-solid fa-cart-shopping"></i></a></li>
                
                {{-- GUNAKAN route('favorites.index') untuk favorit (asumsi nama route ini) --}}
                <li><a href="{{ route('favorites.index') }}"><i class="fa-regular fa-heart"></i></a></li>
                
                <li>|</li>
                <li><a href="{{ route('profile.index') }}">Login</a></li>
            </ul>
        </nav>
    </header>
</div>