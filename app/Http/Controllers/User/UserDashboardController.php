<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EventPlan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserDashboardController extends Controller
{
    /**
     * Display the main dashboard view (Screenshot 3).
     */
    public function dashboard()
    {
        $user = Auth::guard('web')->user();
        
        // Active Subscription details
        $subscription = Subscription::find($user->subscription_id);
        $planName = $subscription ? str_replace(' Plan', '', $subscription->name) : 'Free Trial';
        
        // Fetch plans count
        $plans = EventPlan::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $plansCount = $plans->count();

        // Seed mock plans for display if user has none, to make the dashboard look like Screenshot 3 & 4
        if ($plansCount === 0) {
            $this->seedMockPlans($user->id);
            $plans = EventPlan::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            $plansCount = $plans->count();
        }

        // Latest plan for budget breakdown chart
        $latestPlan = $plans->first();
        
        // If latest plan doesn't have budget shares, generate default ones
        $budgetShares = $latestPlan ? $latestPlan->budget_shares : [
            'Venue' => ['percentage' => 30, 'amount' => 450000],
            'Food' => ['percentage' => 25, 'amount' => 375000],
            'Decor' => ['percentage' => 20, 'amount' => 300000],
            'Photo' => ['percentage' => 15, 'amount' => 225000],
            'Entertainment' => ['percentage' => 10, 'amount' => 150000],
        ];

        return view('user.dashboard', compact('user', 'planName', 'plansCount', 'plans', 'budgetShares', 'latestPlan'));
    }

    /**
     * Display saved event plans (Screenshot 4).
     */
    public function plans()
    {
        $user = Auth::guard('web')->user();
        $plans = EventPlan::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        
        return view('user.plans', compact('plans'));
    }

    /**
     * Duplicate a plan.
     */
    public function duplicatePlan($id)
    {
        $plan = EventPlan::findOrFail($id);
        if ($plan->user_id !== Auth::guard('web')->id()) {
            abort(403);
        }

        $newPlan = $plan->replicate();
        $newPlan->event_type = $plan->event_type;
        $newPlan->style = $plan->style;
        $newPlan->location = $plan->location;
        $newPlan->created_at = now();
        $newPlan->save();

        return redirect()->route('user.plans')
            ->with('success', 'Plan duplicated successfully!');
    }

    /**
     * Delete a plan.
     */
    public function deletePlan($id)
    {
        $plan = EventPlan::findOrFail($id);
        if ($plan->user_id !== Auth::guard('web')->id()) {
            abort(403);
        }

        $plan->delete();

        return redirect()->route('user.plans')
            ->with('success', 'Plan deleted successfully!');
    }

    /**
     * Display profile page (Screenshot 5).
     */
    public function profile()
    {
        $user = Auth::guard('web')->user();
        $subscription = Subscription::find($user->subscription_id);
        $planName = $subscription ? str_replace(' Plan', '', $subscription->name) : 'Basic';
        $priceLabel = $subscription ? '₹' . number_format($subscription->price, 0) . '/mo' : 'Free';

        return view('user.profile', compact('user', 'planName', 'priceLabel'));
    }

    /**
     * Update profile details.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile_number' => ['required', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('user.profile')
            ->with('success', 'Profile details updated successfully!');
    }

    /**
     * Change password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('user.profile')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Helper to seed realistic mock event plans when a new user signs up.
     */
    private function seedMockPlans($userId)
    {
        $mockPlans = [
            [
                'user_id' => $userId,
                'event_type' => 'Wedding',
                'budget' => '₹25 Lakhs - ₹50 Lakhs',
                'guests' => '500 - 1000 guests',
                'location' => 'Bandra, Mumbai',
                'date' => now()->addMonths(5),
                'venue_type' => 'Heritage Palace',
                'food_type' => 'Multi-Cuisine',
                'style' => 'Luxury',
                'decoration_type' => 'Floral Theme',
                'entertainment_type' => 'Live Band',
                'budget_shares' => [
                    'Venue' => ['percentage' => 30, 'amount' => 750000],
                    'Food' => ['percentage' => 25, 'amount' => 625000],
                    'Decor' => ['percentage' => 20, 'amount' => 500000],
                    'Photo' => ['percentage' => 15, 'amount' => 375000],
                    'Entertainment' => ['percentage' => 10, 'amount' => 250000],
                ],
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $userId,
                'event_type' => 'Engagement',
                'budget' => '₹5 Lakhs - ₹10 Lakhs',
                'guests' => '100 - 250 guests',
                'location' => 'Goa',
                'date' => now()->addMonths(7),
                'venue_type' => 'Beach Resort',
                'food_type' => 'Traditional Indian',
                'style' => 'Bohemian',
                'decoration_type' => 'Bohemian Chic',
                'entertainment_type' => 'DJ Only',
                'budget_shares' => [
                    'Venue' => ['percentage' => 40, 'amount' => 320000],
                    'Food' => ['percentage' => 30, 'amount' => 240000],
                    'Decor' => ['percentage' => 15, 'amount' => 120000],
                    'Photo' => ['percentage' => 10, 'amount' => 80000],
                    'Entertainment' => ['percentage' => 5, 'amount' => 40000],
                ],
                'created_at' => now()->subDays(5),
            ],
            [
                'user_id' => $userId,
                'event_type' => 'Reception',
                'budget' => '₹10 Lakhs - ₹25 Lakhs',
                'guests' => '250 - 500 guests',
                'location' => 'Andheri, Mumbai',
                'date' => now()->addMonths(9),
                'venue_type' => 'Banquet Hall',
                'food_type' => 'Continental',
                'style' => 'Modern',
                'decoration_type' => 'Minimalist Elegance',
                'entertainment_type' => 'Classical Singer',
                'budget_shares' => [
                    'Venue' => ['percentage' => 35, 'amount' => 525000],
                    'Food' => ['percentage' => 30, 'amount' => 450000],
                    'Decor' => ['percentage' => 15, 'amount' => 225000],
                    'Photo' => ['percentage' => 12, 'amount' => 180000],
                    'Entertainment' => ['percentage' => 8, 'amount' => 120000],
                ],
                'created_at' => now()->subDays(10),
            ]
        ];

        foreach ($mockPlans as $plan) {
            EventPlan::create($plan);
        }
    }
}
