<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
            'interval' => ['required', 'string', Rule::in(array_keys(Subscription::INTERVALS))],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string', 'max:255'],
        ]);

        $this->validatePrice($validated);
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
            'interval' => ['required', 'string', Rule::in(array_keys(Subscription::INTERVALS))],
            'features' => ['required', 'array'],
            'features.*' => ['required', 'string', 'max:255'],
        ]);

        $this->validatePrice($validated);
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

    private function validatePrice(array &$validated): void
    {
        if ($validated['interval'] === 'free') {
            $validated['price'] = 0;
            return;
        }
        if ((float) $validated['price'] <= 0) {
            throw ValidationException::withMessages(['price' => 'Paid plans must have a price greater than zero.']);
        }
    }
}
