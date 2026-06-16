<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of subscriptions.
     */
    public function index()
    {
        $subscriptions = Subscription::orderBy('price', 'asc')->get();

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Store a newly created subscription.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'interval' => ['required', 'string', 'in:monthly,yearly,lifetime'],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string', 'max:255'],
        ]);

        // Clean up empty feature lines
        $validated['features'] = array_filter($validated['features']);

        Subscription::create($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan added successfully!');
    }

    /**
     * Update the specified subscription.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'interval' => ['required', 'string', 'in:monthly,yearly,lifetime'],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string', 'max:255'],
        ]);

        $validated['features'] = array_filter($validated['features']);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan updated successfully!');
    }

    /**
     * Remove the specified subscription.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan deleted successfully!');
    }
}
