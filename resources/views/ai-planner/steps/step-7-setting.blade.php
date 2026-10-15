<!-- Step 5: Venue Ambience & Dynamic Decor Styling (With High-Resolution Visual Previews) -->
<div x-show="currentStep === {{ $stepNumbers['decoration_type'] ?? -1 }}" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-2.5 sm:space-y-5">
    <div class="space-y-1">
        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-[#850625]/10 text-[#850625] text-[10px] sm:text-xs font-extrabold uppercase tracking-wider sm:tracking-widest">
            <i class="fa-solid fa-wand-magic-sparkles text-[9px] sm:text-[10px]"></i>
            <span>Step {{ str_pad((string) ($stepNumbers['decoration_type'] ?? 0), 2, '0', STR_PAD_LEFT) }} / {{ count($plannerSteps) }} • Ambience & Decor</span>
        </div>
        <h2 class="text-base sm:text-2xl lg:text-3xl font-extrabold text-slate-900 font-serif-luxury leading-snug">{{ $questions->get('decoration_type')?->question ?? 'Select your Venue Vibe & Mandap Decor' }}</h2>
        <p class="text-[11px] sm:text-sm text-slate-600">Visual previews & decor themes tailored for <span class="font-bold text-[#850625]" x-text="planner.culture"></span> tradition.</p>
    </div>

    <!-- 1. Venue Setting Environment Cards (Dynamically Loaded from Admin Question) -->
    <div class="space-y-3">
        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">1. What venue setting environment do you prefer?</span>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
            <template x-for="(settingOption, index) in optionsFor('venue_setting', optionsFor('decoration_type', []))">
                <div @click="selectVenueSetting(settingOption)"
                    :class="planner.setting === settingOption ? 'border-[#850625] shadow-xl ring-2 ring-[#850625]/30 scale-[1.02]' : 'border-slate-200 hover:border-rose-300 hover:shadow-md'"
                    class="bg-white rounded-2xl sm:rounded-3xl border-2 overflow-hidden transition-all cursor-pointer group flex flex-col justify-between">
                    
                    <div class="relative h-24 sm:h-32 w-full overflow-hidden bg-slate-900">
                        <img :src="imageFor('venue_setting', index, imageFor('decoration_type', index, 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=600&q=80'))" :alt="settingOption" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                        <span x-show="planner.setting === settingOption" class="absolute top-2 right-2 sm:top-2.5 sm:right-2.5 bg-[#850625] text-white text-[9px] sm:text-[10px] font-bold px-1.5 sm:px-2 py-0.5 rounded-full shadow">
                            <i class="fa-solid fa-circle-check mr-0.5"></i> Selected
                        </span>
                    </div>

                    <div class="p-2 sm:p-3.5 space-y-0.5 sm:space-y-1">
                        <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate" x-text="settingOption"></h4>
                        <p class="text-[9px] sm:text-[11px] text-slate-500 leading-tight truncate">Venue Ambience</p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- 2. Dynamic Real-Time Filtered Vendor Decor & Venue Packages (Reveals ONLY after user selects Section 1 venue setting) -->
    <div id="ai-vendor-packages-section" x-show="planner.setting" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-3 sm:space-y-4 pt-4 sm:pt-6 border-t border-slate-200 scroll-mt-24 sm:scroll-mt-28">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1">
            <div>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">2. AI-Matched Vendor Decor & Hall Offerings</span>
                <p class="text-[11px] sm:text-xs text-slate-500">Filtered in real-time for <span class="font-bold text-[#850625]" x-text="planner.setting"></span> based on your selected Budget, Guests, Tradition & Locations.</p>
            </div>
            <span class="text-[10px] sm:text-xs font-bold text-[#850625] bg-rose-50 px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full border border-rose-100 self-start sm:self-auto" x-text="getMatchingVendorPackages().length + ' Matches Found'"></span>
        </div>

        <!-- Matching Packages Cards Grid (2 Columns on Mobile) -->
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-5">
            <template x-for="pkg in getMatchingVendorPackages()">
                <div @click="selectPackageAndNext(pkg)"
                    :class="planner.decorTheme === pkg.name ? 'border-[#850625] shadow-xl ring-2 ring-[#850625]/30 scale-[1.01]' : 'border-slate-200 hover:border-rose-300 hover:shadow-md'"
                    class="bg-white rounded-2xl sm:rounded-3xl border-2 overflow-hidden transition-all flex flex-col justify-between group relative cursor-pointer">
                    
                    <div class="relative h-28 sm:h-44 w-full bg-slate-900 overflow-hidden">
                        <template x-if="pkg.images && pkg.images.length">
                            <img :src="pkg.images[0]" :alt="pkg.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </template>
                        <template x-if="!pkg.images || !pkg.images.length">
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-[#850625] to-rose-950 text-white">
                                <i class="fa-solid fa-gem text-2xl sm:text-4xl opacity-30"></i>
                            </div>
                        </template>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>

                        <!-- Selected Badge -->
                        <span x-show="planner.decorTheme === pkg.name" class="absolute top-1.5 right-1.5 sm:top-3 sm:right-3 bg-[#850625] text-white text-[9px] sm:text-xs font-bold px-1.5 sm:px-3 py-0.5 sm:py-1 rounded-full shadow-lg flex items-center gap-1">
                            <i class="fa-solid fa-circle-check text-[8px] sm:text-[10px]"></i> Selected
                        </span>

                        <!-- Budget & Guests Tags -->
                        <div class="absolute bottom-1.5 left-1.5 right-1.5 sm:bottom-3 sm:left-3 sm:right-3 flex items-center justify-between gap-1">
                            <span class="text-[8px] sm:text-[10px] font-extrabold text-slate-950 bg-[#D4AF37] px-1.5 sm:px-2.5 py-0.5 rounded-full shadow-sm truncate" x-text="'₹' + pkg.min_budget + 'L–' + pkg.max_budget + 'L'"></span>
                            <span class="text-[8px] sm:text-[10px] font-bold text-white bg-black/50 backdrop-blur-md px-1.5 sm:px-2 py-0.5 rounded-md truncate hidden xs:inline-block" x-text="pkg.min_capacity + '–' + pkg.max_capacity + ' Guests'"></span>
                        </div>
                    </div>

                    <div class="p-2 sm:p-4 space-y-1.5 sm:space-y-3 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[8px] sm:text-[10px] font-extrabold text-[#850625] uppercase tracking-wider truncate block" x-text="pkg.vendor_name || 'DECORATOR'"></span>
                                <span class="text-[7px] sm:text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate block" x-text="pkg.category"></span>
                            </div>
                            <h4 class="font-extrabold text-slate-900 text-xs sm:text-base leading-snug truncate mt-0.5" x-text="pkg.name"></h4>
                            <p class="text-[10px] sm:text-xs text-slate-500 line-clamp-1 sm:line-clamp-2 mt-0.5 sm:mt-1" x-text="pkg.note"></p>
                        </div>

                        <!-- Card Action Buttons: View Details (Popup) & Select Package -->
                        <div class="pt-1.5 sm:pt-2 border-t border-slate-100 flex items-center justify-between gap-1 sm:gap-2">
                            <button type="button" 
                                @click.stop="openPackageModal(pkg)"
                                class="px-2 sm:px-3.5 py-1 sm:py-1.5 rounded-full border border-slate-300 hover:border-[#850625] text-slate-700 hover:text-[#850625] text-[10px] sm:text-xs font-bold transition-all flex items-center justify-center gap-1 cursor-pointer shrink-0"
                                title="View Details">
                                <i class="fa-solid fa-eye text-[9px] sm:text-[11px]"></i>
                                <span class="hidden sm:inline">Details</span>
                            </button>

                            <button type="button" 
                                @click.stop="selectPackageAndNext(pkg)"
                                :class="planner.decorTheme === pkg.name ? 'bg-[#850625] text-white shadow-sm' : 'bg-rose-50 text-[#850625] hover:bg-[#850625] hover:text-white'"
                                class="flex-1 py-1 sm:py-1.5 px-2 sm:px-4 rounded-full text-[10px] sm:text-xs font-bold transition-all flex items-center justify-center gap-1 cursor-pointer text-center truncate">
                                <span class="truncate" x-text="planner.decorTheme === pkg.name ? 'Selected' : 'Select'"></span>
                                <i class="fa-solid fa-arrow-right text-[8px] sm:text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Fallback Empty State if no vendors match criteria -->
            <div x-show="!getMatchingVendorPackages().length" class="col-span-full bg-rose-50/60 p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-dashed border-rose-200 text-center space-y-2">
                <i class="fa-solid fa-filter text-xl sm:text-2xl text-[#850625]"></i>
                <h4 class="font-bold text-slate-800 text-xs sm:text-sm">No exact package matches for <span x-text="planner.setting"></span></h4>
                <p class="text-[11px] sm:text-xs text-slate-500 max-w-md mx-auto">Try selecting a different venue environment or adjusting your locations / budget in earlier steps.</p>
            </div>
        </div>
    </div>
</div>
