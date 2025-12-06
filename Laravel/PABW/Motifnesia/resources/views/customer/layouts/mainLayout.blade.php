<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Motifnesia</title> {{-- Ganti title biar lebih umum --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"/>

    {{-- local css (pakai asset) --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/partials/slideShow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detailProduk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shoppingCart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paymentConfirmation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/userProfile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/editProfile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modals.css') }}">
    <script src="{{ asset('JS/modal.js') }}"></script>
</head>
<body>
    <main>
        @include('customer.partials.navbar')

        {{-- content dari setiap halaman --}}
        @yield('container') {{-- JANGAN GANTI INI --}}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>