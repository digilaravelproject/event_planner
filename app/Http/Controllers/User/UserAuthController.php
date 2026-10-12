<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\UserWelcomeMail;
use App\Models\User;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserAuthController extends Controller
{
    /**
     * Show the user registration form.
     */
    public function showRegister()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard');
        }

        return view('user.auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'mobile_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.unique' => 'This email address is already registered. Please login or use a different email.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['mobile_number'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('web')->login($user);
        UserQuery::whereNull('user_id')->where('email', $user->email)->update(['user_id' => $user->id]);
        Mail::to($user->email)->send(new UserWelcomeMail($user));

        return redirect()->route('user.subscription')
            ->with('success', 'Account created successfully! Please select a subscription plan to continue.');
    }

    /**
     * Show the user login form.
     */
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.dashboard');
        }

        return view('user.auth.login');
    }

    /**
     * Authenticate user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $user = Auth::guard('web')->user();

            if (! $user->status) {
                Auth::guard('web')->logout();
                $request->session()->regenerate();

                return back()->withErrors([
                    'email' => 'Your account has been deactivated. Please contact the administrator.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();
            UserQuery::whereNull('user_id')->where('email', $user->email)->update(['user_id' => $user->id]);

            if ($request->session()->has('pending_event_plan') && $user->hasActiveSubscription()) {
                return redirect()->route('ai-planner.resume');
            }

            if ($user->hasActiveSubscription()) {
                return redirect()->route('user.dashboard')
                    ->with('success', 'Welcome back!');
            }

            return redirect()->route('user.subscription')
                ->with('success', 'Please subscribe to a plan to start planning events.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log out the user.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->regenerate();

        return redirect()->route('user.login')
            ->with('success', 'Logged out successfully.');
    }
}
