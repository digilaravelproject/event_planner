<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Models\Subarea;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of states, cities, areas, and subareas.
     */
    public function index()
    {
        $states = State::orderBy('name')->get();
        $cities = City::with('state')->orderBy('name')->get();
        $areas = Area::with('city.state')->orderBy('name')->get();
        $subareas = Subarea::with('area.city.state')->orderBy('name')->get();

        return view('admin.areas.index', compact('states', 'cities', 'areas', 'subareas'));
    }

    // --- State CRUD ---
    public function storeState(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:states,name',
        ]);

        State::create($validated);

        return redirect()->route('admin.areas.index', ['tab' => 'states'])
            ->with('success', 'State created successfully!');
    }

    public function updateState(Request $request, State $state)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:states,name,' . $state->id,
        ]);

        $state->update($validated);

        return redirect()->route('admin.areas.index', ['tab' => 'states'])
            ->with('success', 'State updated successfully!');
    }

    public function destroyState(State $state)
    {
        $state->delete();

        return redirect()->route('admin.areas.index', ['tab' => 'states'])
            ->with('success', 'State deleted successfully!');
    }

    // --- City CRUD ---
    public function storeCity(Request $request)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
        ]);

        City::create($validated);

        return redirect()->route('admin.areas.index', ['tab' => 'cities'])
            ->with('success', 'City created successfully!');
    }

    public function updateCity(Request $request, City $city)
    {
        $validated = $request->validate([
            'state_id' => 'required|exists:states,id',
            'name' => 'required|string|max:255',
        ]);

        $city->update($validated);

        return redirect()->route('admin.areas.index', ['tab' => 'cities'])
            ->with('success', 'City updated successfully!');
    }

    public function destroyCity(City $city)
    {
        $city->delete();

        return redirect()->route('admin.areas.index', ['tab' => 'cities'])
            ->with('success', 'City deleted successfully!');
    }

    // --- Area CRUD ---
    public function storeArea(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
        ]);

        Area::create($validated);

        return redirect()->route('admin.areas.index', ['tab' => 'areas'])
            ->with('success', 'Area created successfully!');
    }

    public function updateArea(Request $request, Area $area)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
        ]);

        $area->update($validated);

        return redirect()->route('admin.areas.index', ['tab' => 'areas'])
            ->with('success', 'Area updated successfully!');
    }

    public function destroyArea(Area $area)
    {
        $area->delete();

        return redirect()->route('admin.areas.index', ['tab' => 'areas'])
            ->with('success', 'Area deleted successfully!');
    }

    // --- Subarea CRUD ---
    public function storeSubarea(Request $request)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'name' => 'required|string|max:255',
        ]);

        Subarea::create($validated);

        return redirect()->route('admin.areas.index', ['tab' => 'subareas'])
            ->with('success', 'Subarea created successfully!');
    }

    public function updateSubarea(Request $request, Subarea $subarea)
    {
        $validated = $request->validate([
            'area_id' => 'required|exists:areas,id',
            'name' => 'required|string|max:255',
        ]);

        $subarea->update($validated);

        return redirect()->route('admin.areas.index', ['tab' => 'subareas'])
            ->with('success', 'Subarea updated successfully!');
    }

    public function destroySubarea(Subarea $subarea)
    {
        $subarea->delete();

        return redirect()->route('admin.areas.index', ['tab' => 'subareas'])
            ->with('success', 'Subarea deleted successfully!');
    }
}
