<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\Vendor;
use App\Models\EventPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteRequestController extends Controller
{
    /**
     * Store a quote request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'event_plan_id' => ['required', 'exists:event_plans,id'],
        ]);

        $user = Auth::guard('web')->user();

        // Check if a quote request already exists for this vendor and plan
        $existing = QuoteRequest::where('user_id', $user->id)
            ->where('vendor_id', $validated['vendor_id'])
            ->where('event_plan_id', $validated['event_plan_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'You have already sent a quote request to this vendor for this plan.'
            ]);
        }

        $vendor = Vendor::findOrFail($validated['vendor_id']);

        QuoteRequest::create([
            'user_id' => $user->id,
            'vendor_id' => $validated['vendor_id'],
            'event_plan_id' => $validated['event_plan_id'],
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your quote request has been sent successfully to ' . $vendor->business_name . '!'
        ]);
    }
}
