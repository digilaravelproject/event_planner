<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemMaster;
use App\Models\MasterRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SystemMasterController extends Controller
{
    /**
     * Display a listing of system masters.
     */
    public function index(Request $request)
    {
        // Load registries from database
        $registries = MasterRegistry::orderBy('title', 'asc')->get();
        
        // Reconstruct $allowedTypes for compatibility
        $allowedTypes = $registries->pluck('title', 'key')->toArray();

        // Active type defaults to the first registry key, or 'event_types' if empty
        $defaultType = $registries->first()->key ?? 'event_types';
        $activeType = $request->input('type', $defaultType);
        
        if (!array_key_exists($activeType, $allowedTypes)) {
            $activeType = $defaultType;
        }

        // Get count for each type
        $registryCounts = SystemMaster::selectRaw('type, count(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();

        // Ensure all allowed types have a count entry
        foreach ($allowedTypes as $k => $v) {
            if (!isset($registryCounts[$k])) {
                $registryCounts[$k] = 0;
            }
        }

        // Fetch items for the active type
        $items = SystemMaster::where('type', $activeType)
            ->orderBy('label', 'asc')
            ->get();

        return view('admin.system_masters.index', compact(
            'allowedTypes',
            'activeType',
            'registryCounts',
            'items',
            'registries'
        ));
    }

    /**
     * Store a newly created system master.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string'],
            'label' => ['required', 'string', 'max:255'],
        ]);

        // Check if item already exists under this type
        $exists = SystemMaster::where('type', $validated['type'])
            ->where('label', $validated['label'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'The parameter already exists in this registry.');
        }

        SystemMaster::create($validated);

        return redirect()->route('admin.system-masters.index', ['type' => $validated['type']])
            ->with('success', 'Parameter added successfully!');
    }

    /**
     * Remove the specified system master.
     */
    public function destroy(SystemMaster $systemMaster)
    {
        $type = $systemMaster->type;
        $systemMaster->delete();

        return redirect()->route('admin.system-masters.index', ['type' => $type])
            ->with('success', 'Parameter deleted successfully!');
    }

    /**
     * Store a newly created Master Registry.
     */
    public function storeRegistry(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        // Generate key from title
        $key = Str::slug($validated['title'], '_');

        // Check if key already exists
        $exists = MasterRegistry::where('key', $key)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'A registry with this name already exists.');
        }

        MasterRegistry::create([
            'key' => $key,
            'title' => $validated['title'],
        ]);

        return redirect()->route('admin.system-masters.index', ['type' => $key])
            ->with('success', 'Master Registry created successfully!');
    }

    /**
     * Update the specified Master Registry.
     */
    public function updateRegistry(Request $request, $id)
    {
        $registry = MasterRegistry::findOrFail($id);
        $oldKey = $registry->key;

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        // Generate key from title
        $newKey = Str::slug($validated['title'], '_');

        // Check if new key is already in use by another registry
        $exists = MasterRegistry::where('key', $newKey)->where('id', '!=', $id)->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'A registry with this name already exists.');
        }

        $registry->update([
            'key' => $newKey,
            'title' => $validated['title'],
        ]);

        // Update all parameters in system_masters table that belong to the old key
        if ($oldKey !== $newKey) {
            SystemMaster::where('type', $oldKey)->update(['type' => $newKey]);
        }

        return redirect()->route('admin.system-masters.index', ['type' => $newKey])
            ->with('success', 'Registry renamed successfully!');
    }

    /**
     * Remove the specified Master Registry and its parameters.
     */
    public function destroyRegistry($id)
    {
        $registry = MasterRegistry::findOrFail($id);
        $key = $registry->key;

        // Delete the registry
        $registry->delete();

        // Cascade delete all parameters associated with this registry
        SystemMaster::where('type', $key)->delete();

        return redirect()->route('admin.system-masters.index')
            ->with('success', 'Registry and all its parameters deleted successfully!');
    }
}
