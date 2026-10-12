<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\EventRequirementQuestion;
use App\Modules\DynamicVendors\Http\Requests\DynamicVendorRequest;
use App\Modules\DynamicVendors\Http\Requests\StoreDynamicVendorRequest;
use App\Modules\DynamicVendors\Http\Requests\UpdateDynamicVendorRequest;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Modules\DynamicVendors\Services\AttributeSheetService;
use App\Modules\DynamicVendors\Services\DynamicVendorService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VendorPanelController extends Controller
{
    private const ATTRIBUTE_SUGGESTIONS = [
        'Name', 'Category', 'Price', 'Capacity', 'Location', 'Area', 'Guest Capacity',
        'Min Guest Capacity', 'Max Guest Capacity', 'Min Budget Lakhs', 'Max Budget Lakhs',
        'Supported Locations', 'Decor Category', 'Supported Traditions', 'Decoration Type',
        'Package Details Note', 'Parking', 'Decoration', 'Photography', 'DJ', 'Lighting', 'Sound',
        'Food Type', 'Cuisine', 'Rooms', 'Check In', 'Check Out', 'Drone', 'Album', 'Delivery Time',
    ];

    private const CATEGORY_SUGGESTIONS = [
        'Venue', 'Photographer', 'Decorator', 'DJ', 'Catering', 'Invitation', 'Jewellery',
        'Makeup', 'Travel', 'Hotel', 'Transport', 'Pandit', 'Mehendi', 'Florist', 'Entertainment',
    ];

    public function __construct(
        private readonly DynamicVendorService $service,
        private readonly AttributeSheetService $attributeSheets,
    ) {}

    public function dashboard(): View
    {
        $query = Auth::guard('vendor')->user()->dynamicVendors();

        return view('vendor.dashboard', [
            'totalVendors' => (clone $query)->count(),
            'activeVendors' => (clone $query)->where('status', 'active')->count(),
            'draftVendors' => (clone $query)->where('status', 'draft')->count(),
            'recentVendors' => $query->latest()->take(5)->get(),
        ]);
    }

    public function profile(): View
    {
        return view('vendor.profile', ['vendorAccount' => Auth::guard('vendor')->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $account = Auth::guard('vendor')->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('vendor_accounts')->ignore($account->id)],
            'phone' => ['required', 'string', 'max:30'],
            'category' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:2000'],
            'description' => ['nullable', 'string', 'max:5000'],
        ]);
        $account->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password:vendor'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);
        Auth::guard('vendor')->user()->update(['password' => $data['password']]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive,draft,archived'],
        ]);
        $query = Auth::guard('vendor')->user()->dynamicVendors();
        $query->when($filters['search'] ?? null, fn (Builder $q, string $search) => $q->where('vendor_json', 'like', '%'.addcslashes($search, '%_\\').'%'));
        $query->when($filters['status'] ?? null, fn (Builder $q, string $status) => $q->where('status', $status));

        return view('vendor.vendors.index', ['vendors' => $query->latest()->paginate(15)->withQueryString(), 'filters' => $filters]);
    }

    public function create(): View
    {
        return view('vendor.vendors.create', $this->formOptions());
    }

    public function store(StoreDynamicVendorRequest $request): RedirectResponse
    {
        $account = $request->user('vendor');
        $vendor = $this->service->create($request->validated(), $request->allFiles(), null, $account->id);

        return to_route('vendor.vendors.show', $vendor)->with('success', 'Vendor details created successfully.');
    }

    public function show(DynamicVendor $dynamic_vendor): View
    {
        $this->owned($dynamic_vendor);

        return view('vendor.vendors.show', ['vendor' => $dynamic_vendor]);
    }

    public function edit(DynamicVendor $dynamic_vendor): View
    {
        $this->owned($dynamic_vendor);

        return view('vendor.vendors.edit', array_merge($this->formOptions(), ['vendor' => $dynamic_vendor]));
    }

    public function update(UpdateDynamicVendorRequest $request, DynamicVendor $dynamic_vendor): RedirectResponse
    {
        $this->owned($dynamic_vendor);
        $vendor = $this->service->update($dynamic_vendor, $request->validated(), $request->allFiles(), null);

        return to_route('vendor.vendors.show', $vendor)->with('success', 'Vendor details updated successfully.');
    }

    public function destroy(DynamicVendor $dynamic_vendor): RedirectResponse
    {
        $this->owned($dynamic_vendor);
        $this->service->delete($dynamic_vendor);

        return to_route('vendor.vendors.index')->with('success', 'Vendor details deleted successfully.');
    }

    public function downloadAttributeSample(): BinaryFileResponse
    {
        return response()->download(base_path('app/Modules/DynamicVendors/resources/samples/sample_attribute.xlsx'), 'sample_attribute.xlsx');
    }

    public function importAttributes(Request $request): JsonResponse
    {
        $data = $request->validate(['attribute_sheet' => ['required', 'file', 'max:2048', 'extensions:xlsx']]);

        return response()->json(['attributes' => $this->attributeSheets->import($data['attribute_sheet'])]);
    }

    private function owned(DynamicVendor $vendor): DynamicVendor
    {
        abort_unless($vendor->vendor_account_id === Auth::guard('vendor')->id(), 404);

        return $vendor;
    }

    private function formOptions(): array
    {
        $venueQuestion = EventRequirementQuestion::where('question_code', 'venue_setting')->first();

        return [
            'attributeTypes' => DynamicVendorRequest::TYPES,
            'attributeSuggestions' => self::ATTRIBUTE_SUGGESTIONS,
            'categorySuggestions' => self::CATEGORY_SUGGESTIONS,
            'venueCategories' => array_values(array_unique(array_merge(
                ['Sea-Facing Beachfront', 'Lawn & Poolside', 'Grand AC Ballroom', 'Heritage Resort'],
                $venueQuestion?->options ?? [],
            ))),
        ];
    }
}
