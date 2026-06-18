<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SystemMaster;
use App\Models\BudgetRule;
use App\Models\EventPlan;
use App\Models\Venue;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWizardController extends Controller
{
    /**
     * Show the animated 10-step wizard.
     */
    public function index()
    {
        $event_types = SystemMaster::where('type', 'event_types')->orderBy('label')->get();
        $budget_ranges = SystemMaster::where('type', 'budget_ranges')->get();
        $guest_ranges = SystemMaster::where('type', 'guest_ranges')->get();
        $cities = SystemMaster::where('type', 'cities')->orderBy('label')->get();
        $venue_types = SystemMaster::where('type', 'venue_types')->orderBy('label')->get();
        $food_types = SystemMaster::where('type', 'food_types')->orderBy('label')->get();
        $styles = SystemMaster::where('type', 'styles')->orderBy('label')->get();
        $decoration_types = SystemMaster::where('type', 'decoration_types')->orderBy('label')->get();
        $entertainment_types = SystemMaster::where('type', 'entertainment_types')->orderBy('label')->get();

        return view('user.wizard', compact(
            'event_types',
            'budget_ranges',
            'guest_ranges',
            'cities',
            'venue_types',
            'food_types',
            'styles',
            'decoration_types',
            'entertainment_types'
        ));
    }

    /**
     * Generate and save the event plan.
     */
    public function generatePlan(Request $request)
    {
        $validated = $request->validate([
            'event_type' => ['required', 'string'],
            'budget' => ['required', 'string'],
            'guests' => ['required', 'string'],
            'location' => ['required', 'string'],
            'date' => ['required', 'date'],
            'venue_type' => ['required', 'string'],
            'food_type' => ['required', 'string'],
            'style' => ['required', 'string'],
            'decoration_type' => ['required', 'string'],
            'entertainment_type' => ['required', 'string'],
        ]);

        $user = Auth::guard('web')->user();
        
        // Parse budget to numeric value for split calculations
        $numericBudget = $this->getNumericBudget($validated['budget']);

        // Look up budget rules for shares
        $rule = BudgetRule::where('event_type', $validated['event_type'])
            ->where('style_aesthetic', $validated['style'])
            ->first();

        if (!$rule) {
            // Try matching only by event type
            $rule = BudgetRule::where('event_type', $validated['event_type'])->first();
        }

        // Fallback shares if no rule is matched
        $shares = $rule ? $rule->shares : [
            'Venue' => 40,
            'Food' => 30,
            'Decor' => 15,
            'Photo' => 10,
            'Entertainment' => 5,
        ];

        // Calculate specific rupees for each share category
        $budgetShares = [];
        foreach ($shares as $category => $percentage) {
            $budgetShares[$category] = [
                'percentage' => $percentage,
                'amount' => round(($numericBudget * $percentage) / 100),
            ];
        }

        $plan = EventPlan::create([
            'user_id' => $user->id,
            'event_type' => $validated['event_type'],
            'budget' => $validated['budget'],
            'guests' => $validated['guests'],
            'location' => $validated['location'],
            'date' => $validated['date'],
            'venue_type' => $validated['venue_type'],
            'food_type' => $validated['food_type'],
            'style' => $validated['style'],
            'decoration_type' => $validated['decoration_type'],
            'entertainment_type' => $validated['entertainment_type'],
            'budget_shares' => $budgetShares,
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('user.summary', ['id' => $plan->id]),
            'message' => 'Event plan generated successfully!'
        ]);
    }

    /**
     * Display the generated event summary.
     */
    public function showSummary($id)
    {
        $plan = EventPlan::findOrFail($id);

        // Access check
        if ($plan->user_id !== Auth::guard('web')->id()) {
            abort(403, 'Unauthorized access to this plan.');
        }

        // Recommend venues matching the selected city or capacity
        $venues = Venue::where('status', true)
            ->where(function($query) use ($plan) {
                $query->where('city', 'like', '%' . $plan->location . '%')
                      ->orWhere('city', 'Mumbai'); // Fallback if no specific city matches
            })
            ->take(3)
            ->get();

        // Recommend vendors matching the plan
        $vendors = Vendor::where('status', true)
            ->where(function($query) use ($plan) {
                $query->where('city', 'like', '%' . $plan->location . '%')
                      ->orWhere('city', 'Mumbai');
            })
            ->take(4)
            ->get();

        return view('user.summary', compact('plan', 'venues', 'vendors'));
    }

    /**
     * Parse text budget values into numeric values.
     */
    private function getNumericBudget($budgetString)
    {
        if (str_contains($budgetString, 'Under 5') || str_contains($budgetString, '5 Lakhs')) {
            if (str_contains($budgetString, 'Under')) {
                return 400000;
            }
            return 750000; // 5 - 10
        }
        if (str_contains($budgetString, '10 Lakhs') || str_contains($budgetString, '25 Lakhs')) {
            if (str_contains($budgetString, '10 Lakhs - 25')) {
                return 1750000;
            }
            return 3750000; // 25 - 50
        }
        if (str_contains($budgetString, 'Above 50') || str_contains($budgetString, 'Above')) {
            return 7500000;
        }

        // Search for numbers in string
        preg_match_all('/\d+/', str_replace(',', '', $budgetString), $matches);
        if (!empty($matches[0])) {
            if (count($matches[0]) >= 2) {
                $v1 = (int)$matches[0][0];
                $v2 = (int)$matches[0][1];
                $multiplier = str_contains($budgetString, 'Lakh') ? 100000 : 1;
                return (($v1 + $v2) / 2) * $multiplier;
            }
            $val = (int)$matches[0][0];
            $multiplier = str_contains($budgetString, 'Lakh') ? 100000 : 1;
            return $val * $multiplier;
        }

        return 1500000; // default 15 Lakhs
    }
}
