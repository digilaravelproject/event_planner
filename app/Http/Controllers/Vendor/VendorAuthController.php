<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Mail\AdminVendorRegisteredMail;
use App\Mail\VendorWelcomeMail;
use App\Models\VendorAccount;
use App\Support\EmailRecipients;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class VendorAuthController extends Controller
{
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::guard('vendor')->check()) {
            return to_route('vendor.dashboard');
        }

        return view('vendor.auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:vendor_accounts,email'],
            'phone' => ['required', 'string', 'max:30'],
            'category' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $vendor = VendorAccount::create($data);
        Mail::to($vendor->email)->send(new VendorWelcomeMail($vendor));
        Mail::to(EmailRecipients::ADMIN)->send(new AdminVendorRegisteredMail($vendor));
        Auth::guard('vendor')->login($vendor);
        $request->session()->regenerate();

        return to_route('vendor.dashboard')->with('success', 'Welcome! Your vendor account has been created.');
    }

    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('vendor')->check()) {
            return to_route('vendor.dashboard');
        }

        return view('vendor.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('vendor')->attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
        }

        if (! Auth::guard('vendor')->user()->is_active) {
            Auth::guard('vendor')->logout();

            return back()->withErrors(['email' => 'Your vendor account is inactive. Please contact the administrator.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('vendor.dashboard'))->with('success', 'Welcome back!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('vendor.login')->with('success', 'You have been signed out.');
    }
}
