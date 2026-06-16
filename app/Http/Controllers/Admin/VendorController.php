<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\SystemMaster;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the vendors.
     */
    public function index(Request $request)
    {
        $query = Vendor::query();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // City Filter
        if ($request->filled('city')) {
            $query->where('city', $request->input('city'));
        }

        $vendors = $query->orderBy('created_at', 'desc')->paginate(10);
        
        $categories = SystemMaster::where('type', 'entertainment_types')
            ->orWhere('type', 'food_types')
            ->pluck('label')
            ->merge(['Catering', 'Decor', 'Entertainment', 'Photography', 'Videography', 'Florist', 'Sound System', 'Makeup Artist'])
            ->unique()
            ->sort();

        $cities = SystemMaster::where('type', 'cities')->pluck('label')->unique()->sort();

        return view('admin.vendors.index', compact('vendors', 'categories', 'cities'));
    }

    /**
     * Show the form for creating a new vendor.
     */
    public function create()
    {
        $states = \App\Models\State::orderBy('name')->get();
        return view('admin.vendors.create', compact('states'));
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Owner
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6'],
            // Business
            'business_name' => ['required', 'string', 'max:255'],
            'state_id' => ['required', 'exists:states,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'subarea_id' => ['required', 'exists:subareas,id'],
            // Venue / Price
            'venue_name' => ['required', 'string', 'max:255'],
            'venue_capacity' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'description' => ['nullable', 'string'],
        ]);

        $cityName = \App\Models\State::find($validated['state_id'])?->name ?? 'Mumbai';

        $vendor = Vendor::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'business_name' => $validated['business_name'],
            'category' => 'General',
            'city' => $cityName,
            'state_id' => $validated['state_id'],
            'city_id' => $validated['city_id'],
            'area_id' => $validated['area_id'],
            'subarea_id' => $validated['subarea_id'],
            'base_price' => $validated['base_price'],
            'rating' => $validated['rating'] ?? 5.00,
            'description' => $validated['description'] ?? '',
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'status' => $request->has('status'),
        ]);

        \App\Models\Venue::create([
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

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor listing created successfully!');
    }

    /**
     * Display the specified vendor.
     */
    public function show(Vendor $vendor)
    {
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified vendor.
     */
    public function edit(Vendor $vendor)
    {
        $states = \App\Models\State::orderBy('name')->get();
        $cities = $vendor->state_id ? \App\Models\City::where('state_id', $vendor->state_id)->orderBy('name')->get() : collect();
        $areas = $vendor->city_id ? \App\Models\Area::where('city_id', $vendor->city_id)->orderBy('name')->get() : collect();
        $subareas = $vendor->area_id ? \App\Models\Subarea::where('area_id', $vendor->area_id)->orderBy('name')->get() : collect();
        
        $venue = $vendor->venue;

        return view('admin.vendors.edit', compact('vendor', 'venue', 'states', 'cities', 'areas', 'subareas'));
    }

    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            // Owner
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:vendors,email,' . $vendor->id],
            'phone' => ['required', 'string', 'max:20'],
            // Business
            'business_name' => ['required', 'string', 'max:255'],
            'state_id' => ['required', 'exists:states,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'area_id' => ['required', 'exists:areas,id'],
            'subarea_id' => ['required', 'exists:subareas,id'],
            // Venue / Price
            'venue_name' => ['required', 'string', 'max:255'],
            'venue_capacity' => ['required', 'integer', 'min:1'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'rating' => ['required', 'numeric', 'min:0', 'max:5'],
            'description' => ['nullable', 'string'],
        ]);

        $cityName = \App\Models\State::find($validated['state_id'])?->name ?? 'Mumbai';

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
            'rating' => $validated['rating'],
            'description' => $validated['description'] ?? '',
            'status' => $request->has('status'),
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
            \App\Models\Venue::create([
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

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor listing updated successfully!');
    }

    /**
     * Toggle the active/inactive status of a vendor.
     */
    public function toggleStatus(Vendor $vendor)
    {
        $vendor->status = !$vendor->status;
        $vendor->save();

        return redirect()->back()
            ->with('success', 'Vendor status updated successfully to ' . ($vendor->status ? 'Active' : 'Inactive') . '!');
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Vendor deleted successfully!');
    }
}
