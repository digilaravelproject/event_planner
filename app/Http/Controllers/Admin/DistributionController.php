<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorRegistry;

class DistributionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Query vendors that have at least one registry and eager load registries with eventType
        $vendors = \App\Models\Vendor::whereHas('registries')->with('registries.eventType')->get();
        
        // Transform data into groups of [Vendor, EventType] -> Count
        $groupedDistributions = [];
        
        foreach ($vendors as $vendor) {
            $groupedByEvent = $vendor->registries->groupBy('event_type_id');
            foreach ($groupedByEvent as $eventTypeId => $registries) {
                $eventType = $registries->first()->eventType;
                $groupedDistributions[] = (object) [
                    'vendor' => $vendor,
                    'event_type_id' => $eventTypeId,
                    'event_type_label' => $eventType ? $eventType->label : 'Global / Unassigned',
                    'count' => $registries->count(),
                ];
            }
        }

        return view('admin.distributions.index', compact('groupedDistributions'));
    }

    /**
     * Display the specified resource (View-only distributions for a vendor and event type).
     */
    public function show(string $id, Request $request)
    {
        $vendor = \App\Models\Vendor::with('registries')->findOrFail($id);
        $eventTypeId = $request->query('event_type_id');
        
        $masterRegistries = \App\Models\MasterRegistry::whereNotIn('key', ['event_types', 'event_type', 'city', 'cities', 'states', 'areas', 'subareas'])->get();
        $subregistries = \App\Models\SystemMaster::all()->groupBy('type');
        
        $enabledItems = $vendor->registries()
            ->where('event_type_id', $eventTypeId)
            ->get()
            ->keyBy(function($item) {
                return $item->registry_key . '_' . $item->item_label;
            })->toArray();

        $basePrice = $vendor->base_price ?: 0;
        $costingType = $vendor->costing_type ?: 'percentage';
        
        $eventType = \App\Models\SystemMaster::find($eventTypeId);

        return view('admin.distributions.show', compact('vendor', 'masterRegistries', 'subregistries', 'enabledItems', 'basePrice', 'costingType', 'eventType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vendor = \App\Models\Vendor::findOrFail($id);

        $request->validate([
            'costing_type' => ['required', 'in:percentage,rupees'],
            'event_type_id' => ['required', 'exists:system_masters,id']
        ]);

        $costingType = $request->input('costing_type');
        $eventTypeId = $request->input('event_type_id');
        
        $vendor->update(['costing_type' => $costingType]);

        // Expecting an array of enabled items with their percentages
        // Format: items[registry_key][item_label] = 1
        $itemsInput = $request->input('items', []);
        
        // Remove existing distributions for vendor specifically for this event type
        VendorRegistry::where('vendor_id', $vendor->id)
            ->where('event_type_id', $eventTypeId)
            ->delete();

        // Loop through inputs and insert new entries
        foreach ($itemsInput as $regKey => $labels) {
            foreach ($labels as $label => $enabled) {
                // Read corresponding value from inputs
                $valueKey = "value_{$regKey}_" . str_replace(' ', '_', $label);
                $inputValue = floatval($request->input($valueKey, 0.00));
                
                $percentage = 0.00;
                $rupees = 0.00;

                if ($costingType === 'rupees') {
                    $rupees = $inputValue;
                    if ($vendor->base_price > 0) {
                        $percentage = min(100, ($rupees / $vendor->base_price) * 100);
                    }
                } else {
                    $percentage = $inputValue;
                    if ($vendor->base_price > 0) {
                        $rupees = ($percentage / 100) * $vendor->base_price;
                    }
                }

                VendorRegistry::create([
                    'vendor_id' => $vendor->id,
                    'event_type_id' => $eventTypeId,
                    'registry_key' => $regKey,
                    'item_label' => $label,
                    'share_percentage' => $percentage,
                    'share_rupees' => $rupees,
                    'status' => 1
                ]);
            }
        }

        return redirect()->route('admin.distributions.show', [$vendor->id, 'event_type_id' => $eventTypeId])
            ->with('success', 'Vendor budget distribution updated successfully.');
    }

    public function destroy(string $id, Request $request)
    {
        $request->validate([
            'event_type_id' => ['required', 'exists:system_masters,id']
        ]);

        $eventTypeId = $request->input('event_type_id');
        
        VendorRegistry::where('vendor_id', $id)
            ->where('event_type_id', $eventTypeId)
            ->delete();

        return redirect()->route('admin.distributions.index')->with('success', 'Vendor distribution for the selected event type cleared successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_old(string $id)
    {
        // Destroy all distributions for a vendor
        VendorRegistry::where('vendor_id', $id)->delete();

        return redirect()->route('admin.distributions.index')->with('success', 'Vendor distributions deleted successfully.');
    }
}
