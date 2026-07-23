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
            background-color: #FFFDF9;
            color: #1e293b;
        }
        h1, h2, h3, h4, h5, h6, .font-serif-luxury {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .font-instrument {
            font-family: 'Instrument Serif', Georgia, serif;
        }
        .glassmorphism-light {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
    @stack('styles')
</head>
<body 
    class="bg-[#FFFDF9] text-slate-800 min-h-screen flex flex-col overflow-x-hidden antialiased selection:bg-[#850625]/20 selection:text-[#850625]" 
    :class="{ 'overflow-hidden max-h-screen': !pageReady }"
    x-data="{ isLoaded: false, pageReady: false, scrolled: false }" 
    @scroll.window="scrolled = (window.pageYOffset > 40) ? true : false" 
    x-init="window.scrollTo(0, 0); setTimeout(() => isLoaded = true, 400); setTimeout(() => pageReady = true, 1800)"
>

    <!-- Grand Entrance Loader -->
    @include('web.components.loader')

    <!-- Room Entry Dark Brightness Fade Overlay -->
    <div 
        class="fixed inset-0 z-30 pointer-events-none transition-opacity duration-[2200ms] ease-out bg-black/35"
        :class="isLoaded ? 'opacity-0' : 'opacity-100'"
    ></div>

    <!-- Navigation Header (Fixed Viewport Root) -->
    @include('web.partials.header')

    <!-- 3D Room-Entry Illusion Stage Wrapper -->
    <div 
        class="flex-grow flex flex-col w-full page-3d-stage"
        :class="{
            'perspective-stage': !pageReady,
            'page-3d-initial': !isLoaded,
            'page-3d-zoomed': isLoaded && !pageReady,
            'page-3d-complete': pageReady
        }"
    >
        <!-- Main Content Area -->
        <main class="flex-grow">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('web.partials.footer')
    </div>

    @stack('scripts')
</body>
</html>
