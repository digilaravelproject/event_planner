<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SystemMaster;
use App\Models\BudgetRule;
use App\Models\EventPlan;
use App\Models\Venue;
use App\Models\Vendor;
use App\Models\MasterRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWizardController extends Controller
{
    /**
     * Show the animated 10+ step wizard.
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

        // Load dynamic registries
        $standardKeys = ['event_types', 'budget_ranges', 'guest_ranges', 'food_types', 'venue_types', 'styles', 'entertainment_types', 'decoration_types'];
        $dynamic_registries = MasterRegistry::whereNotIn('key', $standardKeys)->get();
        foreach ($dynamic_registries as $registry) {
            $registry->items = SystemMaster::where('type', $registry->key)->orderBy('label')->get();
        }

        return view('user.wizard', compact(
            'event_types',
            'budget_ranges',
            'guest_ranges',
            'cities',
            'venue_types',
            'food_types',
            'styles',
            'decoration_types',
            'entertainment_types',
            'dynamic_registries'
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
            'state_id' => ['nullable', 'integer'],
            'city_id' => ['nullable', 'integer'],
            'area_id' => ['nullable', 'integer'],
            'subarea_id' => ['nullable', 'integer'],
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

        // Handle dynamic selections
        $standardKeys = ['event_types', 'budget_ranges', 'guest_ranges', 'food_types', 'venue_types', 'styles', 'entertainment_types', 'decoration_types'];
        $dynamicKeys = MasterRegistry::whereNotIn('key', $standardKeys)->pluck('key')->toArray();

        $dynamicSelections = [];
        foreach ($dynamicKeys as $key) {
            if ($request->has($key)) {
                $dynamicSelections[$key] = $request->input($key);
            }
        }

        $plan = EventPlan::create([
            'user_id' => $user->id,
            'event_type' => $validated['event_type'],
            'budget' => $validated['budget'],
            'guests' => $validated['guests'],
            'location' => $validated['location'],
            'state_id' => $validated['state_id'] ?? null,
            'city_id' => $validated['city_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'subarea_id' => $validated['subarea_id'] ?? null,
            'date' => $validated['date'],
            'venue_type' => $validated['venue_type'],
            'food_type' => $validated['food_type'],
            'style' => $validated['style'],
            'decoration_type' => $validated['decoration_type'],
            'entertainment_type' => $validated['entertainment_type'],
            'dynamic_selections' => $dynamicSelections,
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
                if ($plan->city_id) {
                    $query->where('city_id', $plan->city_id);
                } else {
                    $query->where('city', 'like', '%' . $plan->location . '%')
                          ->orWhere('city', 'Mumbai');
                }
            })
            ->take(3)
            ->get();

        // Calculate matched costing details for recommended venue vendors
        foreach ($venues as $venue) {
            if ($venue->vendor) {
                $venue->costing_details = $this->calculateVendorCosting($venue->vendor, $plan);
            } else {
                $venue->costing_details = null;
            }
        }

        // Load all quote requests vendor IDs sent for this plan
        $requestedVendorIds = \App\Models\QuoteRequest::where('event_plan_id', $plan->id)
            ->where('user_id', Auth::guard('web')->id())
            ->pluck('vendor_id')
            ->toArray();

        // 1. Query vendors in user selected area/city
        $vendorQuery = Vendor::where('status', true);
        if ($plan->subarea_id) {
            $vendorQuery->where('subarea_id', $plan->subarea_id);
        } elseif ($plan->area_id) {
            $vendorQuery->where('area_id', $plan->area_id);
        } elseif ($plan->city_id) {
            $vendorQuery->where('city_id', $plan->city_id);
        } else {
            $vendorQuery->where('city', 'like', '%' . $plan->location . '%');
        }

        $vendors = $vendorQuery->get();

        // Fallback if no vendors match geographical specificity
        if ($vendors->isEmpty() && $plan->city_id) {
            $vendors = Vendor::where('status', true)->where('city_id', $plan->city_id)->get();
        }
        if ($vendors->isEmpty()) {
            $vendors = Vendor::where('status', true)->get();
        }

        // 2. Run matched costing calculations for each vendor
        foreach ($vendors as $vendor) {
            $vendor->costing_details = $this->calculateVendorCosting($vendor, $plan);
        }

        // 3. Sort vendors by match count descending and then rating descending
        $sortedVendors = $vendors->sortByDesc(function($v) {
            return [$v->costing_details['match_count'], $v->rating];
        })->values();

        $primaryVendor = null;
        $primaryCosting = null;
        $recommendedVendors = collect();

        if ($sortedVendors->isNotEmpty()) {
            $primaryVendor = $sortedVendors->first();
            $primaryCosting = $primaryVendor->costing_details;
            $recommendedVendors = $sortedVendors->slice(1)->take(4)->values();
        } else {
            // Fallback to standard plan shares if absolutely no vendors exist
            $primaryCosting = [
                'breakdown' => $plan->budget_shares,
                'total_rupees' => array_sum(array_column($plan->budget_shares, 'amount')),
                'total_percentage' => 100,
                'match_count' => 0,
                'total_keys' => 0,
            ];
        }

        return view('user.summary', compact('plan', 'venues', 'primaryVendor', 'primaryCosting', 'recommendedVendors', 'requestedVendorIds'));
    }

    /**
     * Calculate matched costing for a specific vendor on an event plan.
     */
    public function calculateVendorCosting($vendor, $plan)
    {
        // Get the event type ID
        $eventType = SystemMaster::where('type', 'event_types')
            ->where('label', $plan->event_type)
            ->first();
        $eventTypeId = $eventType?->id;

        // User selections to match
        $selections = [
            'venue_types' => $plan->venue_type,
            'food_types' => $plan->food_type,
            'styles' => $plan->style,
            'decoration_types' => $plan->decoration_type,
            'entertainment_types' => $plan->entertainment_type,
        ];

        // Add dynamic selections
        if ($plan->dynamic_selections) {
            foreach ($plan->dynamic_selections as $regKey => $label) {
                $selections[$regKey] = $label;
            }
        }

        // Fetch vendor registries for this event type
        $vendorRegistries = \App\Models\VendorRegistry::where('vendor_id', $vendor->id)
            ->where('event_type_id', $eventTypeId)
            ->get()
            ->keyBy(function($item) {
                return $item->registry_key . '_' . $item->item_label;
            });

        $breakdown = [];
        $totalRupees = 0;
        $totalPercentage = 0;
        $matchCount = 0;

        foreach ($selections as $regKey => $label) {
            $registry = MasterRegistry::where('key', $regKey)->first();
            $displayKey = $registry ? $registry->title : ucwords(str_replace('_', ' ', $regKey));

            $matchKey = $regKey . '_' . $label;
            if ($vendorRegistries->has($matchKey)) {
                $vReg = $vendorRegistries->get($matchKey);
                $percent = floatval($vReg->share_percentage);
                $rupees = floatval($vReg->share_rupees);
                $matchCount++;
            } else {
                $percent = 0.00;
                $rupees = 0.00;
            }

            $breakdown[$displayKey] = [
                'percentage' => $percent,
                'amount' => $rupees,
            ];

            $totalRupees += $rupees;
            $totalPercentage += $percent;
        }

        // Fallback to vendor base price if they have no pricing for selected items
        if ($totalRupees == 0 && $vendor->base_price > 0) {
            $totalRupees = floatval($vendor->base_price);
            $totalPercentage = 100.00;

            $shares = [
                'Venue' => 40,
                'Food' => 30,
                'Decor' => 15,
                'Photo' => 10,
                'Entertainment' => 5,
            ];
            $breakdown = [];
            foreach ($shares as $category => $percentage) {
                $breakdown[$category] = [
                    'percentage' => $percentage,
                    'amount' => round(($totalRupees * $percentage) / 100),
                ];
            }
        }

        return [
            'breakdown' => $breakdown,
            'total_rupees' => $totalRupees,
            'total_percentage' => $totalPercentage,
            'match_count' => $matchCount,
            'total_keys' => count($selections),
        ];
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
