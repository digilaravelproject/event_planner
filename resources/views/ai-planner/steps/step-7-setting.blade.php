<!-- Step 5: Venue Ambience & Dynamic Decor Styling (With High-Resolution Visual Previews) -->
<div x-show="currentStep === 5" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
    <div class="space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#850625]/10 text-[#850625] text-xs font-extrabold uppercase tracking-widest">
            <i class="fa-solid fa-wand-magic-sparkles text-[10px]"></i>
            <span>Step 05 / 07 • Venue Ambience & Decor Visualizer</span>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 font-serif-luxury">{{ $questions->get('decoration_type')?->question ?? 'Select your Venue Vibe & Mandap Decor' }}</h2>
        <p class="text-slate-600 text-sm sm:text-base">Visual previews & decor themes tailored for <span class="font-bold text-[#850625]" x-text="planner.culture"></span> tradition.</p>
    </div>

    <!-- 1. Venue Setting Environment Cards (With Imagery) -->
    <div class="space-y-3">
        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">1. What venue setting environment do you prefer?</span>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <template x-for="setting in [
                { id: 'Sea-Facing Beachfront', title: 'Sea-Facing Beachfront', img: 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80', desc: 'Juhu & Bandra Sunset Beach View' },
                { id: 'Open Air Lawn & Poolside', title: 'Lawn & Poolside', img: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&q=80', desc: 'Under the Stars Ambient Fairy Glow' },
                { id: 'Grand AC Ballroom', title: 'Grand AC Ballroom', img: 'https://images.unsplash.com/photo-1544078751-58fee2d8a03b?auto=format&fit=crop&w=600&q=80', desc: '5-Star Climate-Controlled Luxury' },
                { id: 'Destination Heritage Resort', title: 'Heritage Resort', img: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80', desc: 'South Mumbai Royal Architecture Vibe' }
            ]">
                <div @click="planner.setting = setting.id"
                    :class="planner.setting === setting.id ? 'border-[#850625] shadow-xl ring-2 ring-[#850625]/30 scale-[1.02]' : 'border-slate-200 hover:border-rose-300 hover:shadow-md'"
                    class="bg-white rounded-3xl border-2 overflow-hidden transition-all cursor-pointer group flex flex-col justify-between">
                    
                    <div class="relative h-32 w-full overflow-hidden">
                        <img :src="setting.img" :alt="setting.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <span x-show="planner.setting === setting.id" class="absolute top-2.5 right-2.5 bg-[#850625] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                            <i class="fa-solid fa-circle-check mr-1"></i> Selected
                        </span>
                    </div>

                    <div class="p-3.5 space-y-1">
                        <h4 class="font-bold text-slate-900 text-sm" x-text="setting.title"></h4>
                        <p class="text-[11px] text-slate-500 leading-tight" x-text="setting.desc"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>    <!-- 2. Dynamic Mandap & Decor Themes (Hidden as per request) -->
    <div class="hidden space-y-3 pt-6 border-t border-slate-200">
        <div class="flex items-center justify-between">
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">2. Recommended Mandap & Stage Visual Themes</span>
            <span class="text-xs font-bold text-[#850625]" x-text="'Matching: ' + planner.culture"></span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <template x-for="decor in optionsFor('decoration_type', [
                { id: 'Traditional Marigold & Brass', title: 'Peshwai Marigold & Brass Mandap', range: '₹2.5 Lakh – ₹4.5 Lakh', img: 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=600&q=80', desc: 'Fresh yellow-orange marigold drapes, brass samai lamps & toran entrance.' },
                { id: 'Arabian Night Floral Glow', title: 'Arabian Emerald & Crystal Glow', range: '₹3.5 Lakh – ₹6.0 Lakh', img: 'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=600&q=80', desc: 'Deep green velvet backdrops, fairy lights, crystal chandeliers & rose mandap.' },
                { id: 'Royal Red & Gold Canopy', title: 'Royal Red & Gold Palace Mandap', range: '₹4.0 Lakh – ₹7.5 Lakh', img: 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=600&q=80', desc: 'Carved pillar structures, red velvet drapes, grand arch & royal seating.' },
                { id: 'Temple Lotus & White Mogra', title: 'Temple Lotus & Mogra Fragrance', range: '₹2.0 Lakh – ₹4.0 Lakh', img: 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=600&q=80', desc: 'White jasmine garlands, lotus urlis, banana leaf backdrop & brass lamps.' },
                { id: 'Classic White & Pastel Floral Canopy', title: 'Pastel Rose & Crystal Sunset Canopy', range: '₹3.0 Lakh – ₹5.5 Lakh', img: 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?auto=format&fit=crop&w=600&q=80', desc: 'Blush pink & white orchid ceiling drops, glass aisle & fairy light tunnel.' },
                { id: 'Vibrant Mirrors & Bandhani Theme', title: 'Vibrant Mirror Work & Folk Vibe', range: '₹2.5 Lakh – ₹4.5 Lakh', img: 'https://images.unsplash.com/photo-1532712938310-34cb3982ef74?auto=format&fit=crop&w=600&q=80', desc: 'Colorful umbrella installations, mirror work backdrops & festive seating.' }
            ]).map((value, index) => typeof value === 'object' ? value : ({ id: value, title: value, range: 'Dynamic vendor quote', img: imageFor('decoration_type', index, ['https://images.unsplash.com/photo-1583939003579-730e3918a45a?auto=format&fit=crop&w=600&q=80', 'https://images.unsplash.com/photo-1465495976277-4387d4b0b4c6?auto=format&fit=crop&w=600&q=80', 'https://images.unsplash.com/photo-1519225421980-715cb0215aed?auto=format&fit=crop&w=600&q=80', 'https://images.unsplash.com/photo-1520854221256-17451cc331bf?auto=format&fit=crop&w=600&q=80'][index % 4]), desc: 'Matched against decorator images and active vendor attributes.' }))">
                <div @click="planner.decorTheme = decor.id"
                    :class="planner.decorTheme === decor.id ? 'border-[#850625] shadow-xl ring-2 ring-[#850625]/30 scale-[1.01]' : 'border-slate-200 hover:border-rose-300 hover:shadow-md'"
                    class="bg-white rounded-3xl border-2 overflow-hidden transition-all cursor-pointer group flex flex-col justify-between">
                    
                    <div class="relative h-44 w-full overflow-hidden">
                        <img :src="decor.img" :alt="decor.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
                        
                        <span x-show="planner.decorTheme === decor.id" class="absolute top-3 right-3 bg-[#850625] text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                        </span>

                        <div class="absolute bottom-3 left-3 right-3">
                            <span class="text-[10px] font-extrabold text-slate-950 bg-[#D4AF37] px-2.5 py-0.5 rounded-full inline-block font-sans-ui shadow-sm" x-text="decor.range"></span>
                        </div>
                    </div>

                    <div class="p-4 space-y-1">
                        <h4 class="font-bold text-slate-900 text-sm sm:text-base leading-snug" x-text="decor.title"></h4>
                        <p class="text-xs text-slate-500 leading-relaxed" x-text="decor.desc"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
