<!DOCTYPE html>
<html lang="en" class="min-h-full bg-slate-50/50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Registration - SaaS Event Planner</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Open Sans', 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fe;
        }
        .step-inactive {
            opacity: 0.5;
        }
        .step-active {
            opacity: 1;
            border-color: #3b82f6;
        }
    </style>
</head>
<body class="min-h-screen py-12 flex flex-col justify-center items-center px-4 relative bg-[#f8f9fe]">

    <!-- Top Blue background block -->
    <div class="absolute top-0 left-0 w-full bg-[#5e72e4] h-[340px] z-0"></div>

    <div class="w-full max-w-2xl z-10 space-y-6">
        <!-- Brand Identity -->
        <div class="text-center text-white mb-4">
            <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white text-blue-600 shadow-md shadow-blue-500/25 mb-3 ring-4 ring-white/20">
                <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </span>
            <h2 class="text-2xl font-bold tracking-tight">Register Your Business</h2>
            <p class="text-white/80 text-xs mt-1 font-medium font-semibold">Join our premium event planner workspace network</p>
        </div>

        <!-- Floating White Card -->
        <div class="bg-white border border-slate-100 shadow-xl rounded-2xl p-8 ring-1 ring-slate-100/50 transition duration-200">
            
            <!-- Tab Indicator Header -->
            <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
                <div id="step-indicator-1" class="flex-1 text-center pb-2 border-b-2 border-blue-500 font-bold text-xs text-blue-600 uppercase tracking-wider pb-4">
                    1. Owner Details
                </div>
                <div id="step-indicator-2" class="flex-1 text-center pb-2 border-b-2 border-transparent font-bold text-xs text-slate-400 uppercase tracking-wider step-inactive pb-4">
                    2. Business & Venue Details
                </div>
            </div>

            <form action="{{ route('vendor.register.submit') }}" method="POST" id="registration-form" class="space-y-6">
                @csrf

                <!-- TAB 1: Owner Details -->
                <div id="tab-1" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Contact Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Contact Name</label>
                            <div class="relative">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150"
                                    placeholder="John Doe">
                                <span class="absolute left-3.5 top-3.5 text-slate-400">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                                    </svg>
                                </span>
                            </div>
                            @error('name') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Email Address</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150"
                                    placeholder="john@business.com">
                                <span class="absolute left-3.5 top-3.5 text-slate-400">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                    </svg>
                                </span>
                            </div>
                            @error('email') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Phone number -->
                        <div class="sm:col-span-2">
                            <label for="phone" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Phone Number</label>
                            <div class="relative">
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150"
                                    placeholder="e.g. +91 9876543210">
                                <span class="absolute left-3.5 top-3.5 text-slate-400">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.302a12.017 12.017 0 0 1-4.773-4.773c-.24-.44-.074-.927.302-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                                    </svg>
                                </span>
                            </div>
                            @error('phone') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-10 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150"
                                    placeholder="••••••••">
                                <span class="absolute left-3.5 top-3.5 text-slate-400">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                </span>
                                <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3.5 top-3 text-slate-400 focus:outline-none hover:text-slate-600" style="right: 1pc !important;">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Confirm Password</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-10 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150"
                                    placeholder="••••••••">
                                <span class="absolute left-3.5 top-3.5 text-slate-400">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                </span>
                                <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)" class="absolute right-3.5 top-3 text-slate-400 focus:outline-none hover:text-slate-600" style="right: 1pc !important;">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="button" onclick="goToTab2()"
                            class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 transition shadow-md shadow-blue-500/10 focus:outline-none">
                            Next: Business Info &rarr;
                        </button>
                    </div>
                </div>

                <!-- TAB 2: Business & Venue Details -->
                <div id="tab-2" class="space-y-4 hidden">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Business Name -->
                        <div class="sm:col-span-2">
                            <label for="business_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Business Brand Name</label>
                            <input type="text" name="business_name" id="business_name" value="{{ old('business_name') }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150"
                                placeholder="e.g. Elegant Weddings">
                            @error('business_name') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- State Selector -->
                        <div>
                            <label for="state_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">State</label>
                            <select name="state_id" id="state_id" required onchange="loadLocations('city', this.value)"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-500 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                                <option value="">Choose State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('state_id') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- City Selector -->
                        <div>
                            <label for="city_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">City</label>
                            <select name="city_id" id="city_id" required onchange="loadLocations('area', this.value)"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-500 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                                <option value="">Choose City</option>
                            </select>
                            @error('city_id') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Area Selector -->
                        <div>
                            <label for="area_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Area</label>
                            <select name="area_id" id="area_id" required onchange="loadLocations('subarea', this.value)"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-500 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                                <option value="">Choose Area</option>
                            </select>
                            @error('area_id') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Subarea Selector -->
                        <div>
                            <label for="subarea_id" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Subarea</label>
                            <select name="subarea_id" id="subarea_id" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-500 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                                <option value="">Choose Subarea</option>
                            </select>
                            @error('subarea_id') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Venue Name -->
                        <div>
                            <label for="venue_name" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Venue Display Name</label>
                            <input type="text" name="venue_name" id="venue_name" required placeholder="e.g. Royal Crystal Palace"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                            @error('venue_name') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Capacity (Guests) -->
                        <div>
                            <label for="venue_capacity" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Max Venue Capacity (Guests)</label>
                            <input type="number" name="venue_capacity" id="venue_capacity" required placeholder="e.g. 500" min="1"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                            @error('venue_capacity') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Pricing (Costing) -->
                        <div class="sm:col-span-2">
                            <label for="base_price" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Base Cost / Pricing (INR per event)</label>
                            <input type="number" step="0.01" name="base_price" id="base_price" required placeholder="e.g. 75000.00"
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150">
                            @error('base_price') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-2">
                            <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Business Description & Packages</label>
                            <textarea name="description" id="description" rows="3" placeholder="Tell couples about your services, venue amenities, and package inclusions..."
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-slate-700 text-xs focus:outline-none focus:ring-1 focus:ring-blue-500 transition duration-150"></textarea>
                            @error('description') <p class="text-[10px] text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50 mt-4">
                        <button type="button" onclick="goToTab1()"
                            class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold uppercase tracking-wider px-5 py-3 transition">
                            &larr; Back
                        </button>
                        <button type="submit" 
                            class="rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase tracking-wider px-6 py-3 transition shadow-md shadow-blue-500/15 hover:shadow-blue-500/25 active:scale-[0.99] focus:outline-none">
                            Create Vendor Account
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 border-t border-slate-100 pt-4 text-center">
                <p class="text-xs text-slate-400 font-semibold">
                    Already registered? <a href="{{ route('vendor.login') }}" class="text-blue-600 hover:underline">Log in here</a>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Email validation state
        let emailIsValidated = true;

        async function checkEmailExistence() {
            const emailInput = document.getElementById('email');
            const email = emailInput.value.trim();
            if (!email) return;

            // Remove any existing warning messages first
            const existingWarn = document.getElementById('email-warning');
            if (existingWarn) existingWarn.remove();

            try {
                const response = await fetch(`/vendor/check-email?email=${encodeURIComponent(email)}`);
                const data = await response.json();
                if (data.exists) {
                    emailIsValidated = false;
                    emailInput.classList.add('border-rose-500', 'bg-rose-50/10');
                    
                    const warning = document.createElement('p');
                    warning.id = 'email-warning';
                    warning.className = 'text-[10px] text-rose-500 mt-1 font-semibold';
                    warning.innerText = 'This email is already registered. Please choose another or log in.';
                    emailInput.closest('.relative').after(warning);
                } else {
                    emailIsValidated = true;
                    emailInput.classList.remove('border-rose-500', 'bg-rose-50/10');
                }
            } catch (error) {
                console.error('Error checking email:', error);
            }
        }

        document.getElementById('email').addEventListener('blur', checkEmailExistence);

        // Password visibility toggler
        function togglePasswordVisibility(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('svg');
            if (field.type === 'password') {
                field.type = 'text';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M9 9a3 3 0 000 6m6-6a3 3 0 010 6M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />`;
            } else {
                field.type = 'password';
                icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />`;
            }
        }

        // Tab switching controller
        async function goToTab2() {
            // Basic validation check for Tab 1 inputs
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const pass = document.getElementById('password').value;
            const confirmPass = document.getElementById('password_confirmation').value;

            if (!name || !email || !phone || !pass || !confirmPass) {
                alert('Please fill out all Owner Details before continuing.');
                return;
            }

            if (pass !== confirmPass) {
                alert('Passwords do not match.');
                return;
            }

            // Trigger final check for email validation
            await checkEmailExistence();

            if (!emailIsValidated) {
                alert('This email is already registered. Please use a different email address.');
                return;
            }

            // Toggle tab views
            document.getElementById('tab-1').classList.add('hidden');
            document.getElementById('tab-2').classList.remove('hidden');

            // Toggle indicators
            const ind1 = document.getElementById('step-indicator-1');
            const ind2 = document.getElementById('step-indicator-2');
            ind1.classList.add('step-inactive', 'border-transparent');
            ind1.classList.remove('border-blue-500', 'text-blue-600');
            ind1.classList.add('text-slate-400');
            
            ind2.classList.remove('step-inactive', 'border-transparent', 'text-slate-400');
            ind2.classList.add('border-blue-500', 'text-blue-600');
        }

        function goToTab1() {
            // Toggle tab views
            document.getElementById('tab-2').classList.add('hidden');
            document.getElementById('tab-1').classList.remove('hidden');

            // Toggle indicators
            const ind1 = document.getElementById('step-indicator-1');
            const ind2 = document.getElementById('step-indicator-2');
            ind2.classList.add('step-inactive', 'border-transparent');
            ind2.classList.remove('border-blue-500', 'text-blue-600');
            ind2.classList.add('text-slate-400');
            
            ind1.classList.remove('step-inactive', 'border-transparent', 'text-slate-400');
            ind1.classList.add('border-blue-500', 'text-blue-600');
        }

        // Ajax loader for Location selections
        async function loadLocations(level, parentId) {
            let url = '';
            let targetSelect = null;
            let selectsToReset = [];

            if (level === 'city') {
                url = `/locations/states/${parentId}/cities`;
                targetSelect = document.getElementById('city_id');
                selectsToReset = ['city_id', 'area_id', 'subarea_id'];
            } else if (level === 'area') {
                url = `/locations/cities/${parentId}/areas`;
                targetSelect = document.getElementById('area_id');
                selectsToReset = ['area_id', 'subarea_id'];
            } else if (level === 'subarea') {
                url = `/locations/areas/${parentId}/subareas`;
                targetSelect = document.getElementById('subarea_id');
                selectsToReset = ['subarea_id'];
            }

            // Reset selection inputs
            selectsToReset.forEach(id => {
                const select = document.getElementById(id);
                if (select) {
                    select.innerHTML = `<option value="">Choose ${id.replace('_id', '').replace(/^\w/, c => c.toUpperCase())}</option>`;
                }
            });

            if (!parentId) return;

            try {
                const response = await fetch(url);
                const data = await response.json();
                
                data.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.innerText = item.name;
                    targetSelect.appendChild(opt);
                });
            } catch (error) {
                console.error('Error loading location data:', error);
            }
        }

        // Form submit validator
        document.getElementById('registration-form').addEventListener('submit', async function(e) {
            await checkEmailExistence();
            if (!emailIsValidated) {
                e.preventDefault();
                alert('Please resolve the email duplicate issue before registering.');
            }
        });
    </script>
</body>
</html>
