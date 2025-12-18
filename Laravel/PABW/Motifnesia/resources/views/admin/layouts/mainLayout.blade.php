{{-- resources/views/admin/layouts/mainLayout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title')</title>
    
    {{-- Tailwind CSS (via Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- Jika perlu, tambahkan library CSS seperti Bootstrap di sini --}}
</head>
<body style="margin: 0;">
    <div class="admin-container">
        {{-- TERUSKAN $activePage KE SIDEBAR --}}
        @include('admin.components.sidebar', ['activePage' => $activePage ?? 'default'])

        <div class="main-content">
            {{-- Header/Navbar (yang ada search bar dan profil) --}}
            @include('admin.components.navbar') 

            {{-- Konten Utama Halaman --}}
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>