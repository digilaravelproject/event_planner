<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSubscriptionController extends Controller
{
    /**
     * Show the subscription plans page.
     */
    public function index()
    {
        $plans = Subscription::all();
        return view('user.subscription', compact('plans'));
    }

    /**
     * Verify Razorpay payment and subscribe the user.
     */
    public function verifyPayment(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:subscriptions,id'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['nullable', 'string'],
            'razorpay_signature' => ['nullable', 'string'],
            'billing_cycle' => ['required', 'string', 'in:monthly,yearly'],
        ]);

        $user = Auth::guard('web')->user();
        $plan = Subscription::findOrFail($validated['plan_id']);

        // Set expiration date based on selected billing cycle
        $endsAt = $validated['billing_cycle'] === 'yearly' 
            ? now()->addYear() 
            : now()->addMonth();

        $user->update([
            'subscription_id' => $plan->id,
            'subscription_ends_at' => $endsAt,
            'razorpay_payment_id' => $validated['razorpay_payment_id'],
            'razorpay_order_id' => $validated['razorpay_order_id'],
        ]);

        return response()->json([
            'success' => true,
            'redirect' => route('user.profile'),
            'message' => 'Subscription activated successfully!',
        ]);
    }
}
