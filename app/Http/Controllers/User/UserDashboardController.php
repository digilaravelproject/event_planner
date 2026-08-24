<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::guard('web')->user();
        $recentPlans = $user->eventPlans()->whereNull('parent_plan_id')->withCount('suggestions')->latest()->take(5)->get();
        $statistics = [
            'plans' => $user->eventPlans()->whereNull('parent_plan_id')->count(),
            'alternatives' => $user->eventPlans()->whereNotNull('parent_plan_id')->count(),
            'latest_budget' => (float) ($recentPlans->first()?->total_cost ?? 0),
        ];

        return view('user.dashboard', compact('user', 'recentPlans', 'statistics'));
    }

    public function profile()
    {
        $user = Auth::guard('web')->user();
        $subscription = Subscription::find($user->subscription_id);
        $planName = $subscription ? str_replace(' Plan', '', $subscription->name) : 'No plan';
        $priceLabel = $subscription ? '₹'.number_format($subscription->price, 0).'/mo' : 'Free';
        $subscriptionFlag = $user->subscriptionFlag();

        return view('user.profile', compact('user', 'planName', 'priceLabel', 'subscriptionFlag'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'mobile_number' => ['required', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return to_route('user.profile')->with('success', 'Profile details updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::guard('web')->user();
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        return to_route('user.profile')->with('success', 'Password updated successfully!');
    }
}
