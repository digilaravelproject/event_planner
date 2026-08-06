<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Shaadi Sense | Luxury Event Planning & Management')</title>
    <meta name="description" content="@yield('meta_description', 'Experience magnificent luxury event planning with Shaadi Sense. From royal weddings to grand celebrations.')">

    <!-- Google Fonts: Luxury Typography Palette -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Vite Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Force Scroll to Top on Refresh / Hard Refresh -->
    <script>
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }
        window.scrollTo(0, 0);
        window.addEventListener('beforeunload', function() {
            window.scrollTo(0, 0);
        });
    </script>

    <!-- Three.js CDN for WebGL Shader Canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #FFFDF9;
            color: #1e293b;
        }
        h1, h2, h3, h4, h5, h6, .font-serif-luxury {
            font-family: 'Cormorant Garamond', 'Playfair Display', Georgia, serif;
        }
        .font-cinzel {
            font-family: 'Cinzel', Georgia, serif;
        }
        .font-cormorant {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }
        .font-playfair {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .font-sans-ui {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
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

        /* Scroll Reveal Dynamic Animation Classes */
        .scroll-reveal {
            opacity: 0;
            transform: translateY(32px) scale(0.98);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            will-change: opacity, transform;
        }
        .scroll-reveal.is-revealed {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    </style>
    @stack('styles')
</head>
<body 
    class="bg-[#FFFDF9] text-slate-800 min-h-screen flex flex-col overflow-x-hidden antialiased selection:bg-[#850625]/20 selection:text-[#850625]" 
    :class="{ 'overflow-hidden max-h-screen': !pageReady }"
    x-data="{ isLoaded: false, pageReady: false, scrolled: false }" 
    @scroll.window="scrolled = (window.pageYOffset > 40) ? true : false" 
    x-init="window.scrollTo(0, 0); setTimeout(() => isLoaded = true, 400); setTimeout(() => { pageReady = true; window.scrollTo(0, 0); }, 1800)"
>
    <!-- Top Luxury Scroll Progress Indicator Line -->
    <div id="scroll-progress-bar" class="fixed top-0 left-0 h-[3px] bg-gradient-to-r from-[#850625] via-[#D4AF37] to-[#850625] z-[100] transition-all duration-75 w-0 pointer-events-none"></div>

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

    <!-- Floating Scroll-To-Top Quick Button -->
    <button 
        x-show="scrolled" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 scale-90"
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-6 right-6 z-40 w-11 h-11 rounded-full bg-[#850625] text-white border-2 border-[#D4AF37] shadow-xl shadow-[#850625]/30 flex items-center justify-center hover:bg-[#6b041e] hover:scale-110 active:scale-95 transition-all duration-300 group"
        title="Scroll to Top"
        style="display: none;"
    >
        <i class="fa-solid fa-arrow-up text-xs text-[#D4AF37] group-hover:-translate-y-0.5 transition-transform"></i>
    </button>

    <!-- Custom Royal Shaadi Theme Mouse Cursor -->
    <div id="custom-cursor-dot" class="hidden md:block fixed top-0 left-0 w-3.5 h-3.5 rounded-full bg-[#850625] border-2 border-[#D4AF37] shadow-md shadow-[#850625]/40 pointer-events-none z-[9999] transition-transform duration-75"></div>
    <div id="custom-cursor-ring" class="hidden md:block fixed top-0 left-0 w-9 h-9 rounded-full border-2 border-[#850625]/35 bg-[#850625]/[0.04] backdrop-blur-[1px] pointer-events-none z-[9998] transition-all duration-200"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Top Scroll Progress Indicator Bar
            const progressBar = document.getElementById('scroll-progress-bar');
            window.addEventListener('scroll', function() {
                if (!progressBar) return;
                const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                const scrolled = (winScroll / height) * 100;
                progressBar.style.width = scrolled + '%';
            });

            // 2. IntersectionObserver Scroll Reveal Animations (Exclude Hero Section)
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-revealed');
                    }
                });
            }, {
                threshold: 0.05,
                rootMargin: '0px 0px -30px 0px'
            });

            // Target lower sections only, NEVER the hero section
            const revealTargets = document.querySelectorAll('section:not(#hero-section) h2, section:not(#hero-section) .grid');
            revealTargets.forEach(target => {
                target.classList.add('scroll-reveal');
                revealObserver.observe(target);
            });

            // 3. Custom Cursor Follower Logic
            const dot = document.getElementById('custom-cursor-dot');
            const ring = document.getElementById('custom-cursor-ring');
            if (!dot || !ring || window.innerWidth < 768) return;

            let mouseX = -100, mouseY = -100;
            let ringX = -100, ringY = -100;

            window.addEventListener('mousemove', function(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;
                dot.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0) translate(-50%, -50%)`;
            });

            function animateRing() {
                ringX += (mouseX - ringX) * 0.18;
                ringY += (mouseY - ringY) * 0.18;
                ring.style.transform = `translate3d(${ringX}px, ${ringY}px, 0) translate(-50%, -50%)`;
                requestAnimationFrame(animateRing);
            }
            animateRing();

            // Hover effect on interactive elements
            const updateHoverListeners = () => {
                const interactiveElements = document.querySelectorAll('a, button, input, select, textarea, [role="button"], .group, .clickable');
                interactiveElements.forEach(el => {
                    el.addEventListener('mouseenter', () => {
                        ring.style.width = '3.25rem';
                        ring.style.height = '3.25rem';
                        ring.style.borderColor = '#850625';
                        ring.style.backgroundColor = 'rgba(133, 6, 37, 0.12)';
                        ring.style.boxShadow = '0 0 20px rgba(133, 6, 37, 0.25)';
                        dot.style.backgroundColor = '#D4AF37';
                        dot.style.borderColor = '#850625';
                    });
                    el.addEventListener('mouseleave', () => {
                        ring.style.width = '2.25rem';
                        ring.style.height = '2.25rem';
                        ring.style.borderColor = 'rgba(133, 6, 37, 0.35)';
                        ring.style.backgroundColor = 'rgba(133, 6, 37, 0.04)';
                        ring.style.boxShadow = 'none';
                        dot.style.backgroundColor = '#850625';
                        dot.style.borderColor = '#D4AF37';
                    });
                });
            };

            updateHoverListeners();
        });
    </script>

    @stack('scripts')
</body>
</html>
