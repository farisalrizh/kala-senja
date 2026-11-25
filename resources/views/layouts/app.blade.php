<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f1e8">
    <meta name="description" content="Kala Senja — tempat singgah untuk kopi yang baik, makanan rumahan, dan obrolan yang tak terburu-buru.">
    <title>@yield('title', 'Kala Senja — Temukan jeda, temukan rasa')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/kala-senja.css') }}">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}" aria-label="Kala Senja, beranda">
            <span class="brand-mark">ks<span>.</span></span>
            <span class="brand-name">kala senja<small>coffee & slow living</small></span>
        </a>
        @if (auth()->check())
            <nav class="site-nav admin-nav" aria-label="Navigasi admin">
                <a href="{{ route('admin.menus.index') }}">Kelola menu</a>
                <a href="{{ route('admin.orders.index') }}">Pesanan</a>
                <a href="{{ route('admin.reservations.index') }}">Reservasi</a>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button class="nav-logout" type="submit">Keluar</button>
                </form>
            </nav>
        @elseif (request()->routeIs('admin.login'))
            <nav class="site-nav" aria-label="Navigasi">
                <a href="{{ route('home') }}">Kembali ke beranda</a>
            </nav>
        @else
            <nav class="site-nav" aria-label="Navigasi utama">
                <a href="#cerita">Cerita kami</a>
                <a href="#menu">Menu</a>
                <a href="#kunjungi">Kunjungi</a>
                <a class="nav-cta" href="#reservasi">Pesan meja <span aria-hidden="true">↗</span></a>
            </nav>
        @endif
    </header>

    @yield('content')

    <footer class="site-footer">
        <a class="brand footer-brand" href="{{ route('home') }}">
            <span class="brand-mark">ks<span>.</span></span>
            <span class="brand-name">kala senja<small>coffee & slow living</small></span>
        </a>
        <p>Diracik pelan-pelan, dinikmati sepenuh hati.</p>
        <a class="footer-admin" href="{{ route('admin.login') }}">Ruang admin</a>
        <span class="footer-copy">© {{ date('Y') }} Kala Senja</span>
    </footer>
    <script src="{{ asset('js/kala-senja.js') }}" defer></script>
</body>
</html>
