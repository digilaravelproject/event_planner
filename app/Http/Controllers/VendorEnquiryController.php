<?php

namespace App\Http\Controllers;

use App\Models\VendorEnquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorEnquiryController extends Controller
{
    /**
     * Show the public vendor enquiry form.
     */
    public function create()
    {
        $categories = VendorEnquiry::CATEGORIES;

        return view('web.vendor-enquiry.create', compact('categories'));
    }

    /**
     * Store a newly created vendor enquiry in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name'   => 'required|string|max:255',
            'contact_no'      => 'required|string|max:50',
            'email'           => 'required|email|max:255',
            'location'        => 'required|string|max:255',
            'vendor_category' => 'required|string|max:255',
            'service'         => 'required|string',
            'capacity'        => 'nullable|string|max:255',
            'price'           => 'nullable|string|max:255',
            'packages'        => 'nullable|string',
            'owner_name'      => 'required|string|max:255',
            'owner_number'    => 'required|string|max:50',
            'owner_email'     => 'required|email|max:255',
            'media.*'         => 'nullable|file|mimes:jpeg,png,jpg,webp,mp4,mov,avi,webm,pdf|max:25600', // max 25MB per file
        ], [
            'business_name.required'   => 'Business name is required.',
            'contact_no.required'      => 'Business contact number is required.',
            'email.required'           => 'Business email is required.',
            'location.required'        => 'Business location is required.',
            'vendor_category.required' => 'Please select a vendor category.',
            'service.required'         => 'Please provide details about your services.',
            'owner_name.required'      => 'Owner name is required.',
            'owner_number.required'    => 'Owner contact number is required.',
            'owner_email.required'     => 'Owner email address is required.',
            'media.*.max'              => 'Each photo or video must be under 25MB.',
        ]);

        $uploadedMedia = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                if ($file->isValid()) {
                    $path = $file->store('vendor-enquiries', 'public');
                    $uploadedMedia[] = [
                        'path' => $path,
                        'name' => $file->getClientOriginalName(),
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                    ];
                }
            }
        }

        VendorEnquiry::create([
            'business_name'   => $validated['business_name'],
            'contact_no'      => $validated['contact_no'],
            'email'           => $validated['email'],
            'location'        => $validated['location'],
            'vendor_category' => $validated['vendor_category'],
            'service'         => $validated['service'],
            'capacity'        => $validated['capacity'] ?? null,
            'price'           => $validated['price'] ?? null,
            'packages'        => $validated['packages'] ?? null,
            'media_files'     => !empty($uploadedMedia) ? $uploadedMedia : null,
            'owner_name'      => $validated['owner_name'],
            'owner_number'    => $validated['owner_number'],
            'owner_email'     => $validated['owner_email'],
            'status'          => 'pending',
        ]);

        return redirect()->route('vendor-enquiry.create')->with('success', 'Your vendor enquiry has been submitted successfully! Our team will review your business profile shortly.');
    }
}
