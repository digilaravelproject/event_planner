<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Shaadi Sense | Luxury Event Planning & Management')</title>
    <meta name="description" content="@yield('meta_description', 'Experience magnificent luxury event planning with Shaadi Sense. From royal weddings to grand celebrations.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FAF8F5;
        }
        h1, h2, h3, h4, h5, h6, .font-serif-luxury {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .font-instrument {
            font-family: 'Instrument Serif', Georgia, serif;
        }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(133, 6, 37, 0.08);
        }
    </style>
    @stack('styles')
</head>
<body 
    class="bg-[#FAF8F5] text-slate-800 min-h-screen flex flex-col overflow-x-hidden antialiased selection:bg-[#850625]/20 selection:text-[#850625]" 
    x-data="{ isLoaded: false, scrolled: false }" 
    @scroll.window="scrolled = (window.pageYOffset > 40) ? true : false" 
    x-init="setTimeout(() => isLoaded = true, 1200)"
>

    <!-- Grand Entrance Loader -->
    @include('web.components.loader')

    <!-- Navigation Header -->
    @include('web.partials.header')

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('web.partials.footer')

    @stack('scripts')
</body>
</html>
