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

    <!-- 1. Venue Setting Environment Cards (Loaded Dynamically from Question Management) -->
    <div class="space-y-3">
        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">1. What venue setting environment do you prefer?</span>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <template x-for="(settingOption, index) in optionsFor('decoration_type', [
                'Sea-Facing Beachfront',
                'Lawn & Poolside',
                'Grand AC Ballroom',
                'Heritage Resort'
            ])">
                <div @click="planner.setting = settingOption"
                    :class="planner.setting === settingOption ? 'border-[#850625] shadow-xl ring-2 ring-[#850625]/30 scale-[1.02]' : 'border-slate-200 hover:border-rose-300 hover:shadow-md'"
                    class="bg-white rounded-3xl border-2 overflow-hidden transition-all cursor-pointer group flex flex-col justify-between">
                    
                    <div class="relative h-32 w-full overflow-hidden bg-slate-900">
                        <img :src="imageFor('decoration_type', index, [
                            'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1544078751-58fee2d8a03b?auto=format&fit=crop&w=600&q=80',
                            'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=600&q=80'
                        ][index % 4])" :alt="settingOption" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <span x-show="planner.setting === settingOption" class="absolute top-2.5 right-2.5 bg-[#850625] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow">
                            <i class="fa-solid fa-circle-check mr-1"></i> Selected
                        </span>
                    </div>

                    <div class="p-3.5 space-y-1">
                        <h4 class="font-bold text-slate-900 text-sm" x-text="settingOption"></h4>
                        <p class="text-[11px] text-slate-500 leading-tight">Admin Managed Venue Category Vibe</p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- 2. Dynamic Real-Time Filtered Vendor Decor & Venue Packages -->
    <div class="space-y-4 pt-6 border-t border-slate-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
            <div>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">2. AI-Matched Vendor Decor & Hall Offerings</span>
                <p class="text-xs text-slate-500">Filtered in real-time based on your selected Budget, Guests, Tradition & Locations.</p>
            </div>
            <span class="text-xs font-bold text-[#850625] bg-rose-50 px-3 py-1 rounded-full border border-rose-100 self-start sm:self-auto" x-text="getMatchingVendorPackages().length + ' Matches Found'"></span>
        </div>

        <!-- Matching Packages Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <template x-for="pkg in getMatchingVendorPackages()">
                <div :class="planner.decorTheme === pkg.decor_type ? 'border-[#850625] shadow-xl ring-2 ring-[#850625]/30 scale-[1.01]' : 'border-slate-200 hover:border-rose-300 hover:shadow-md'"
                    class="bg-white rounded-3xl border-2 overflow-hidden transition-all flex flex-col justify-between group relative">
                    
                    <div class="relative h-44 w-full bg-slate-900 overflow-hidden">
                        <template x-if="pkg.images && pkg.images.length">
                            <img :src="pkg.images[0]" :alt="pkg.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </template>
                        <template x-if="!pkg.images || !pkg.images.length">
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-[#850625] to-rose-950 text-white">
                                <i class="fa-solid fa-gem text-4xl opacity-30"></i>
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

                        <!-- Selected Badge -->
                        <span x-show="planner.decorTheme === pkg.decor_type" class="absolute top-3 right-3 bg-[#850625] text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Selected
                        </span>

                        <!-- Budget Tag -->
                        <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between">
                            <span class="text-[10px] font-extrabold text-slate-950 bg-[#D4AF37] px-2.5 py-0.5 rounded-full shadow-sm" x-text="'₹' + pkg.min_budget + 'L – ₹' + pkg.max_budget + 'L'"></span>
                            <span class="text-[10px] font-bold text-white bg-black/40 backdrop-blur-md px-2 py-0.5 rounded-md" x-text="pkg.min_capacity + ' – ' + pkg.max_capacity + ' Guests'"></span>
                        </div>
                    </div>

                    <div class="p-4 space-y-3">
                        <div>
                            <span class="text-[10px] font-bold text-[#850625] uppercase tracking-wider block" x-text="pkg.category"></span>
                            <h4 class="font-extrabold text-slate-900 text-base leading-snug truncate" x-text="pkg.name"></h4>
                            <p class="text-xs text-slate-500 line-clamp-2 mt-1" x-text="pkg.note"></p>
                        </div>

                        <!-- Card Action Buttons: View Details (Popup) & Select Package -->
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                            <button type="button" 
                                @click="openPackageModal(pkg)"
                                class="px-3.5 py-1.5 rounded-full border border-slate-300 hover:border-[#850625] text-slate-700 hover:text-[#850625] text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                                <i class="fa-solid fa-eye text-[11px]"></i>
                                <span>View Details</span>
                            </button>

                            <button type="button" 
                                @click="planner.decorTheme = pkg.decor_type"
                                :class="planner.decorTheme === pkg.decor_type ? 'bg-[#850625] text-white' : 'bg-rose-50 text-[#850625] hover:bg-[#850625] hover:text-white'"
                                class="px-4 py-1.5 rounded-full text-xs font-bold transition-all flex items-center gap-1 cursor-pointer">
                                <span x-text="planner.decorTheme === pkg.decor_type ? 'Selected' : 'Select'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Fallback Empty State if no vendors match criteria -->
            <div x-show="!getMatchingVendorPackages().length" class="col-span-full bg-rose-50/60 p-8 rounded-3xl border border-dashed border-rose-200 text-center space-y-2">
                <i class="fa-solid fa-filter text-2xl text-[#850625]"></i>
                <h4 class="font-bold text-slate-800 text-sm">No exact package matches for selected criteria</h4>
                <p class="text-xs text-slate-500 max-w-md mx-auto">Try adjusting your locations or guest count range in earlier steps to view available offerings.</p>
            </div>
        </div>
    </div>
</div>
