<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorEnquiryController extends Controller
{
    /**
     * Display a listing of vendor enquiries.
     */
    public function index(Request $request)
    {
        $query = VendorEnquiry::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('vendor_category', $request->category);
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('contact_no', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $enquiries = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => VendorEnquiry::count(),
            'pending'   => VendorEnquiry::where('status', 'pending')->count(),
            'contacted' => VendorEnquiry::where('status', 'contacted')->count(),
            'approved'  => VendorEnquiry::where('status', 'approved')->count(),
            'rejected'  => VendorEnquiry::where('status', 'rejected')->count(),
        ];

        $categories = VendorEnquiry::CATEGORIES;

        return view('admin.vendor-enquiries.index', compact('enquiries', 'stats', 'categories'));
    }

    /**
     * Display the specified vendor enquiry details.
     */
    public function show(VendorEnquiry $vendorEnquiry)
    {
        return view('admin.vendor-enquiries.show', compact('vendorEnquiry'));
    }

    /**
     * Update status and notes for the vendor enquiry.
     */
    public function update(Request $request, VendorEnquiry $vendorEnquiry)
    {
        $validated = $request->validate([
            'status'      => 'required|in:pending,contacted,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $vendorEnquiry->update($validated);

        return redirect()->back()->with('success', 'Enquiry status and notes updated successfully.');
    }

    /**
     * Remove the specified vendor enquiry from database.
     */
    public function destroy(VendorEnquiry $vendorEnquiry)
    {
        // Delete uploaded files if any
        if (!empty($vendorEnquiry->media_files) && is_array($vendorEnquiry->media_files)) {
            foreach ($vendorEnquiry->media_files as $file) {
                if (isset($file['path'])) {
                    Storage::disk('public')->delete($file['path']);
                }
            }
        }

        $vendorEnquiry->delete();

        return redirect()->route('admin.vendor-enquiries.index')->with('success', 'Vendor enquiry deleted successfully.');
    }
}
