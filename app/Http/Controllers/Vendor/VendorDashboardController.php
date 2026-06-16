<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Venue;
use App\Models\VendorRegistry;
use App\Models\MasterRegistry;
use App\Models\SystemMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Subarea;

class VendorDashboardController extends Controller
{
    /**
     * Display the Vendor Dashboard.
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();
        $venue = $vendor->venue;
        $registries = $vendor->registries;

        $states = State::orderBy('name')->get();
        $cities = $vendor->state_id ? City::where('state_id', $vendor->state_id)->orderBy('name')->get() : collect();
        $areas = $vendor->city_id ? Area::where('city_id', $vendor->city_id)->orderBy('name')->get() : collect();
        $subareas = $vendor->area_id ? Subarea::where('area_id', $vendor->area_id)->orderBy('name')->get() : collect();

        return view('vendor.dashboard', compact('vendor', 'venue', 'registries', 'states', 'cities', 'areas', 'subareas'));
    }

    /**
     * Show form to edit vendor business profile and venue parameters.
     */
    public function editBusiness()
    {
        $vendor = Auth::guard('vendor')->user();
        $venue = $vendor->venue;

        $states = State::orderBy('name')->get();
        $cities = $vendor->state_id ? City::where('state_id', $vendor->state_id)->orderBy('name')->get() : collect();
        $areas = $vendor->city_id ? Area::where('city_id', $vendor->city_id)->orderBy('name')->get() : collect();
        $subareas = $vendor->area_id ? Subarea::where('area_id', $vendor->area_id)->orderBy('name')->get() : collect();

        return view('vendor.business', compact('vendor', 'venue', 'states', 'cities', 'areas', 'subareas'));
    }

    /**
     * Update vendor business profile and venue parameters.
     */
    public function updateBusiness(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        $validated = $request->validate([
            // Owner Info
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors,email,' . $vendor->id],
            'phone' => ['required', 'string', 'max:20'],
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

        $cityName = State::find($validated['state_id'])?->name ?? 'Mumbai';

        $vendor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'business_name' => $validated['business_name'],
            'city' => $cityName,
            'state_id' => $validated['state_id'],
            'city_id' => $validated['city_id'],
            'area_id' => $validated['area_id'],
            'subarea_id' => $validated['subarea_id'],
            'base_price' => $validated['base_price'],
            'description' => $validated['description'] ?? '',
        ]);

        $venue = $vendor->venue;
        if ($venue) {
            $venue->update([
                'name' => $validated['venue_name'],
                'city' => $cityName,
                'state_id' => $validated['state_id'],
                'city_id' => $validated['city_id'],
                'area_id' => $validated['area_id'],
                'subarea_id' => $validated['subarea_id'],
                'capacity' => $validated['venue_capacity'],
                'price_per_day' => $validated['base_price'],
            ]);
        } else {
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
        }

        return redirect()->route('vendor.business.edit')
            ->with('success', 'Profile and business details updated successfully!');
    }

    /**
     * Show form to select registries and manage budget distributions.
     */
    public function editBudget()
    {
        $vendor = Auth::guard('vendor')->user();
        
        // Load all registries configured by admin
        $masterRegistries = MasterRegistry::all();
        
        // Load subregistries (system masters) grouped by registry key
        $subregistries = SystemMaster::all()->groupBy('type');
        
        // Load vendor's enabled registry items
        $enabledItems = $vendor->registries->keyBy(function($item) {
            return $item->registry_key . '_' . $item->item_label;
        })->toArray();

        return view('vendor.budget', compact('vendor', 'masterRegistries', 'subregistries', 'enabledItems'));
    }

    /**
     * Save registries and budget distribution.
     */
    public function updateBudget(Request $request)
    {
        $vendor = Auth::guard('vendor')->user();

        // Expecting an array of enabled items with their percentages
        // Format: items[registry_key][item_label] = share_percentage (or just registry keys / labels checked)
        $itemsInput = $request->input('items', []);
        
        // Remove existing distributions for vendor
        VendorRegistry::where('vendor_id', $vendor->id)->delete();

        // Loop through inputs and insert new entries
        foreach ($itemsInput as $regKey => $labels) {
            foreach ($labels as $label => $enabled) {
                // Read corresponding percentage from percentage inputs
                $percentageKey = "percent_{$regKey}_" . str_replace(' ', '_', $label);
                $percentage = floatval($request->input($percentageKey, 0.00));

                VendorRegistry::create([
                    'vendor_id' => $vendor->id,
                    'registry_key' => $regKey,
                    'item_label' => $label,
                    'share_percentage' => $percentage,
                ]);
            }
        }

        return redirect()->route('vendor.budget.edit')
            ->with('success', 'Registry options and budget distribution saved successfully!');
    }
}
