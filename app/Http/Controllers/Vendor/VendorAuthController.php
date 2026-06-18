<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\Venue;
use App\Models\State;
use App\Models\SystemMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class VendorAuthController extends Controller
{
    /**
     * Show registration form.
     */
    public function showRegister()
    {
        $states = State::orderBy('name')->get();
        return view('vendor.auth.register', compact('states'));
    }

    /**
     * Register a new vendor.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Owner Info
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            // Business Details
            'business_name' => ['required', 'string', 'max:255'],
            'state_id' => ['required', 'exists:states,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'subarea_id' => ['required', 'exists:subareas,id'],
            // Venue & Costing details
            'venue_name' => ['required', 'string', 'max:255'],
            'venue_capacity' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        // Find city name for backwards compatibility
        $cityName = State::find($validated['state_id'])?->name ?? 'Mumbai';

        $vendor = Vendor::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'business_name' => $validated['business_name'],
            'category' => 'General', // Default vendor category
            'city' => $cityName,
            'state_id' => $validated['state_id'],
            'city_id' => $validated['city_id'],
            'area_id' => $validated['area_id'],
            'subarea_id' => $validated['subarea_id'],
            'base_price' => $validated['base_price'],
            'description' => $validated['description'] ?? '',
            'password' => Hash::make($validated['password']),
            'status' => true,
            'rating' => 5.00,
        ]);

        // Auto create linked venue
        Venue::create([
            'vendor_id' => $vendor->id,
            'name' => $validated['venue_name'],
            'city' => $cityName,
            'state_id' => $validated['state_id'],
            'city_id' => $validated['city_id'],
            'area_id' => $validated['area_id'],
            'subarea_id' => $validated['subarea_id'],
            'capacity' => $validated['venue_capacity'],
            'price_per_day' => $validated['base_price'],
            'status' => true,
        ]);

        Auth::guard('vendor')->login($vendor);

        return redirect()->route('vendor.dashboard')
            ->with('success', 'Registration successful! Welcome to your dashboard.');
    }

    /**
     * Show login form.
     */
    public function showLogin()
    {
        return view('vendor.auth.login');
    }

    /**
     * Authenticate vendor login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('vendor')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('vendor.dashboard')
                ->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Logout vendor.
     */
    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();

        $request->session()->regenerate();

        return redirect()->route('vendor.login')
            ->with('success', 'Logged out successfully!');
    }

    /**
     * Check if email already exists in vendor table.
     */
    public function checkEmail(Request $request)
    {
        $email = $request->query('email');
        $exists = Vendor::where('email', $email)->exists();
        return response()->json(['exists' => $exists]);
    }
}
