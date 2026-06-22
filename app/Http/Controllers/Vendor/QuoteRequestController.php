<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuoteRequestController extends Controller
{
    /**
     * Display a listing of received quote requests.
     */
    public function index()
    {
        $vendor = Auth::guard('vendor')->user();

        $quoteRequests = QuoteRequest::with(['user', 'eventPlan'])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->get();

        // Calculate matched pricing specifically for this vendor on each requested plan
        $wizardController = new \App\Http\Controllers\User\UserWizardController();
        foreach ($quoteRequests as $req) {
            if ($req->eventPlan) {
                $req->costing_details = $wizardController->calculateVendorCosting($vendor, $req->eventPlan);
            } else {
                $req->costing_details = null;
            }
        }

        return view('vendor.quote_requests.index', compact('quoteRequests'));
    }

    /**
     * Remove the specified quote request.
     */
    public function destroy($id)
    {
        $vendor = Auth::guard('vendor')->user();

        $request = QuoteRequest::where('vendor_id', $vendor->id)
            ->where('id', $id)
            ->firstOrFail();

        $request->delete();

        return redirect()->route('vendor.quote-requests.index')
            ->with('success', 'Quote enquiry deleted successfully.');
    }
}
