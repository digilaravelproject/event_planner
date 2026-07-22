<footer class="bg-[#850625] text-white pt-16 pb-12 px-6 md:px-12 border-t border-rose-900/50">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
        <!-- Brand Info -->
        <div class="space-y-4">
            <div class="flex items-center space-x-3">
                <span class="bg-white text-[#850625] w-10 h-10 rounded-full flex items-center justify-center font-bold text-xl shadow-md">
                    <svg class="h-5 w-5 text-[#850625]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2c-.1 3.2-2.8 5.9-6 6 3.2.1 5.9 2.8 6 6 .1-3.2 2.8-5.9 6-6-3.2-.1-5.9-2.8-6-6z"/>
                    </svg>
                </span>
                <span class="font-serif-luxury text-xl font-bold tracking-wide text-white">
                    Shaadi <span class="text-[#D4AF37] font-sans font-extrabold text-lg">Sense</span>
                </span>
            </div>
            <p class="text-sm text-rose-100 leading-relaxed font-medium">
                Crafting royal memories and seamless event planning experiences. From luxury weddings to milestone celebrations, we execute every detail with elegance.
            </p>
            <div class="flex space-x-3 pt-2">
                <a href="#" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white hover:text-[#850625] flex items-center justify-center transition-colors text-xs text-white"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white hover:text-[#850625] flex items-center justify-center transition-colors text-xs text-white"><i class="fab fa-twitter"></i></a>
                <a href="#" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white hover:text-[#850625] flex items-center justify-center transition-colors text-xs text-white"><i class="fab fa-instagram"></i></a>
                <a href="#" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white hover:text-[#850625] flex items-center justify-center transition-colors text-xs text-white"><i class="fab fa-pinterest"></i></a>
            </div>
        </div>

        <!-- Quick Links -->
        <div>
            <h4 class="text-[#D4AF37] font-serif-luxury font-extrabold text-lg mb-4">Quick Links</h4>
            <ul class="space-y-2.5 text-sm text-rose-100 font-medium">
                <li><a href="#categories" class="hover:text-white hover:underline transition-all">Event Categories</a></li>
                <li><a href="#why-choose-us" class="hover:text-white hover:underline transition-all">Why Choose Us</a></li>
                <li><a href="#estimator" class="hover:text-white hover:underline transition-all">Cost Estimator</a></li>
                <li><a href="#testimonials" class="hover:text-white hover:underline transition-all">Client Reviews</a></li>
            </ul>
        </div>

        <!-- Partner Portals -->
        <div>
            <h4 class="text-[#D4AF37] font-serif-luxury font-extrabold text-lg mb-4">Partner Portals</h4>
            <ul class="space-y-2.5 text-sm text-rose-100 font-medium">
                <li><a href="{{ route('vendor.login') }}" class="hover:text-white transition-colors"><i class="fa-solid fa-store mr-1.5 text-xs text-[#D4AF37]"></i> Vendor Portal</a></li>
                <li><a href="{{ route('vendor.register') }}" class="hover:text-white transition-colors"><i class="fa-solid fa-user-plus mr-1.5 text-xs text-[#D4AF37]"></i> Join as Vendor</a></li>
                <li><a href="{{ route('admin.login') }}" class="hover:text-white transition-colors"><i class="fa-solid fa-lock mr-1.5 text-xs text-[#D4AF37]"></i> Admin Login</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div>
            <h4 class="text-[#D4AF37] font-serif-luxury font-extrabold text-lg mb-4">Contact Us</h4>
            <ul class="space-y-3 text-sm text-rose-100 font-medium">
                <li class="flex items-start space-x-3">
                    <i class="fa-solid fa-location-dot mt-1 text-[#D4AF37] text-xs"></i>
                    <span>123 Celebration Boulevard, Suite 500, Mumbai, India</span>
                </li>
                <li class="flex items-center space-x-3">
                    <i class="fa-solid fa-phone text-[#D4AF37] text-xs"></i>
                    <span>+91 98765 43210</span>
                </li>
                <li class="flex items-center space-x-3">
                    <i class="fa-solid fa-envelope text-[#D4AF37] text-xs"></i>
                    <span>hello@shaadi-sense.com</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Copyright -->
    <div class="max-w-7xl mx-auto border-t border-white/20 pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-rose-100 font-medium">
        <p>&copy; {{ date('Y') }} Shaadi Sense. All rights reserved.</p>
        <div class="flex space-x-6 mt-4 md:mt-0">
            <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
        </div>
    </div>
</footer>
