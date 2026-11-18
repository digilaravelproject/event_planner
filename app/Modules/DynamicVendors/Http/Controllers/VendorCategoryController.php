<?php

namespace App\Modules\DynamicVendors\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\DynamicVendors\Http\Requests\DynamicVendorRequest;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Modules\DynamicVendors\Models\VendorCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VendorCategoryController extends Controller
{
    public function index(): View
    {
        return view('dynamic-vendors::categories.index', [
            'categories' => VendorCategory::query()->orderBy('sort_order')->orderBy('name')->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('dynamic-vendors::categories.form', [
            'category' => new VendorCategory(['is_active' => true]),
            'attributeTypes' => DynamicVendorRequest::TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        VendorCategory::create($this->validated($request));

        return redirect()->route('admin.vendor-categories.index')->with('success', 'Vendor category created successfully.');
    }

    public function edit(VendorCategory $vendor_category): View
    {
        return view('dynamic-vendors::categories.form', [
            'category' => $vendor_category,
            'attributeTypes' => DynamicVendorRequest::TYPES,
        ]);
    }

    public function update(Request $request, VendorCategory $vendor_category): RedirectResponse
    {
        $oldName = $vendor_category->name;
        $vendor_category->update($this->validated($request, $vendor_category));

        if ($oldName !== $vendor_category->name) {
            DynamicVendor::query()->where('vendor_json->identity->category', $oldName)->get()
                ->each(function (DynamicVendor $vendor) use ($vendor_category): void {
                    $document = $vendor->vendor_json;
                    data_set($document, 'identity.category', $vendor_category->name);
                    $vendor->update(['vendor_json' => $document]);
                });
        }

        return redirect()->route('admin.vendor-categories.index')->with('success', 'Vendor category updated successfully.');
    }

    public function destroy(VendorCategory $vendor_category): RedirectResponse
    {
        $inUse = DynamicVendor::query()->where('vendor_json->identity->category', $vendor_category->name)->exists();
        if ($inUse) {
            return back()->with('error', 'This category is assigned to vendors. Reassign those vendors before deleting it.');
        }

        $vendor_category->delete();

        return back()->with('success', 'Vendor category deleted successfully.');
    }

    private function validated(Request $request, ?VendorCategory $category = null): array
    {
        $request->merge([
            'slug' => Str::slug((string) ($request->input('slug') ?: $request->input('name'))),
        ]);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('vendor_categories')->ignore($category)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('vendor_categories')->ignore($category)],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'fields' => ['nullable', 'array'],
            'fields.*.label' => ['required', 'string', 'max:255'],
            'fields.*.type' => ['required', Rule::in(DynamicVendorRequest::TYPES)],
            'fields.*.help' => ['nullable', 'string', 'max:500'],
            'fields.*.options' => ['nullable', 'string'],
            'fields.*.unit' => ['nullable', Rule::in(['fixed', 'per_guest', 'per_unit', 'per_hour', 'per_day'])],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['fields'] = collect($validated['fields'] ?? [])->map(function (array $field): array {
            $field['help'] = trim((string) ($field['help'] ?? ''));
            $field['options'] = array_values(array_filter(array_map('trim', explode(',', (string) ($field['options'] ?? '')))));
            $field['unit'] = $field['unit'] ?? 'fixed';

            return $field;
        })->values()->all();

        return $validated;
    }
}
