<?php

namespace App\Modules\DynamicVendors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DynamicVendors\Http\Requests\DynamicVendorRequest;
use App\Modules\DynamicVendors\Http\Requests\StoreDynamicVendorRequest;
use App\Modules\DynamicVendors\Http\Requests\UpdateDynamicVendorRequest;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Modules\DynamicVendors\Models\DynamicVendorVersion;
use App\Modules\DynamicVendors\Repositories\DynamicVendorRepositoryInterface;
use App\Modules\DynamicVendors\Services\AttributeSheetService;
use App\Modules\DynamicVendors\Services\DynamicVendorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DynamicVendorController extends Controller
{
    private const SUGGESTIONS = [
        'Name', 'Category', 'Price', 'Capacity', 'Location', 'Area', 'Guest Capacity',
        'Min Guest Capacity', 'Max Guest Capacity', 'Min Budget Lakhs', 'Max Budget Lakhs',
        'Supported Locations', 'Decor Category', 'Supported Traditions', 'Decoration Type',
        'Package Details Note', 'Parking', 'Decoration', 'Photography', 'DJ', 'Lighting', 'Sound', 'Food Type',
        'Cuisine', 'Rooms', 'Check In', 'Check Out', 'Drone', 'Album', 'Delivery Time',
    ];

    private const CATEGORY_SUGGESTIONS = [
        'Venue', 'Photographer', 'Decorator', 'DJ', 'Catering', 'Invitation', 'Jewellery',
        'Makeup', 'Travel', 'Hotel', 'Transport', 'Pandit', 'Mehendi', 'Florist', 'Entertainment',
    ];

    public function __construct(
        private readonly DynamicVendorRepositoryInterface $vendors,
        private readonly DynamicVendorService $service,
        private readonly AttributeSheetService $attributeSheets,
    ) {}

    public function downloadAttributeSample(): BinaryFileResponse
    {
        return response()->download(
            __DIR__.'/../../resources/samples/sample_attribute.xlsx',
            'sample_attribute.xlsx',
        );
    }

    public function importAttributes(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'attribute_sheet' => ['required', 'file', 'max:2048', 'extensions:xlsx'],
        ]);

        return response()->json([
            'attributes' => $this->attributeSheets->import($validated['attribute_sheet']),
        ]);
    }

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive,draft,archived'],
            'category' => ['nullable', 'string', 'max:255'],
            'sort' => ['nullable', 'in:name,category,status,created_at'],
            'direction' => ['nullable', 'in:asc,desc'],
        ]);

        return view('dynamic-vendors::index', [
            'vendors' => $this->vendors->paginate($filters),
            'categories' => $this->vendors->categories(),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('dynamic-vendors::create', $this->formOptions());
    }

    public function store(StoreDynamicVendorRequest $request): RedirectResponse
    {
        $vendor = $this->service->create($request->validated(), $request->allFiles(), $request->user('admin')?->id);

        return redirect()->route('admin.dynamic-vendors.show', $vendor)->with('success', 'Dynamic vendor created successfully.');
    }

    public function show(DynamicVendor $dynamic_vendor): View
    {
        $dynamic_vendor->load(['versions.creator', 'creator', 'updater']);

        return view('dynamic-vendors::show', ['vendor' => $dynamic_vendor]);
    }

    public function edit(DynamicVendor $dynamic_vendor): View
    {
        return view('dynamic-vendors::edit', array_merge($this->formOptions(), ['vendor' => $dynamic_vendor]));
    }

    public function update(UpdateDynamicVendorRequest $request, DynamicVendor $dynamic_vendor): RedirectResponse
    {
        $vendor = $this->service->update($dynamic_vendor, $request->validated(), $request->allFiles(), $request->user('admin')?->id);

        return redirect()->route('admin.dynamic-vendors.show', $vendor)->with('success', 'Dynamic vendor updated successfully.');
    }

    public function destroy(Request $request, DynamicVendor $dynamic_vendor): RedirectResponse
    {
        $this->service->delete($dynamic_vendor);

        return redirect()->route('admin.dynamic-vendors.index')->with('success', 'Dynamic vendor deleted successfully.');
    }

    public function duplicate(Request $request, DynamicVendor $dynamic_vendor): RedirectResponse
    {
        $copy = $this->service->duplicate($dynamic_vendor, $request->user('admin')?->id);

        return redirect()->route('admin.dynamic-vendors.edit', $copy)->with('success', 'Vendor duplicated as a draft.');
    }

    public function status(Request $request, DynamicVendor $dynamic_vendor): RedirectResponse
    {
        $validated = $request->validate(['status' => ['required', 'in:active,inactive,draft,archived']]);
        $this->service->changeStatus($dynamic_vendor, $validated['status'], $request->user('admin')?->id);

        return back()->with('success', 'Vendor status updated.');
    }

    public function rollback(Request $request, DynamicVendor $dynamic_vendor, DynamicVendorVersion $version): RedirectResponse
    {
        $this->service->rollback($dynamic_vendor, $version, $request->user('admin')?->id);

        return back()->with('success', "Version {$version->version} restored as a new version.");
    }

    private function formOptions(): array
    {
        $venueQuestion = \App\Models\EventRequirementQuestion::where('question_code', 'venue_setting')->first();
        $venueCategories = array_values(array_unique(array_merge(
            ['Sea-Facing Beachfront', 'Lawn & Poolside', 'Grand AC Ballroom', 'Heritage Resort'],
            $venueQuestion?->options ?? []
        )));

        return [
            'attributeTypes' => DynamicVendorRequest::TYPES,
            'attributeSuggestions' => self::SUGGESTIONS,
            'categorySuggestions' => self::CATEGORY_SUGGESTIONS,
            'venueCategories' => $venueCategories,
        ];
    }
}
