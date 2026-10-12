<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventQuestionRequest;
use App\Models\AdminModuleOption;
use App\Models\EventRequirementQuestion;
use App\Services\VendorAttributeCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventRequirementQuestionController extends Controller
{
    public function __construct(private readonly VendorAttributeCatalogService $vendorAttributes) {}

    public function index(Request $request)
    {
        $validated = $request->validate(['search' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', 'in:0,1'], 'sort' => ['nullable', 'in:question,question_code,question_type,display_order,status,created_at'], 'direction' => ['nullable', 'in:asc,desc']]);
        $sort = $validated['sort'] ?? 'display_order';
        $direction = $validated['direction'] ?? 'asc';
        $questions = EventRequirementQuestion::query()->when($validated['search'] ?? null, fn ($q, $s) => $q->where(fn ($q) => $q->where('question', 'like', "%$s%")->orWhere('question_code', 'like', "%$s%")))->when(isset($validated['status']), fn ($q) => $q->where('status', $validated['status']))->orderBy($sort, $direction)->paginate(15)->withQueryString();

        return view('admin.event-questions.index', compact('questions'));
    }

    public function create()
    {
        $question = new EventRequirementQuestion;

        return view('admin.event-questions.form', ['question' => $question, 'types' => AdminModuleOption::forGroup('question_type')->get(), 'attributeCatalog' => $this->vendorAttributes->catalog()]);
    }

    public function store(StoreEventQuestionRequest $request)
    {
        $data = $this->vendorAttributes->applyMapping($request->validated());
        $data = $this->applyCategoryOptions($request, $data);

        DB::transaction(fn () => EventRequirementQuestion::create($data));

        return to_route('admin.event-questions.index')->with('success', 'Question created.');
    }

    public function show(EventRequirementQuestion $eventQuestion)
    {
        return view('admin.event-questions.show', ['question' => $eventQuestion]);
    }

    public function edit(EventRequirementQuestion $eventQuestion)
    {
        return view('admin.event-questions.form', ['question' => $eventQuestion, 'types' => AdminModuleOption::forGroup('question_type')->get(), 'attributeCatalog' => $this->vendorAttributes->catalog($eventQuestion)]);
    }

    public function update(StoreEventQuestionRequest $request, EventRequirementQuestion $eventQuestion)
    {
        $data = $this->vendorAttributes->applyMapping($request->validated(), $eventQuestion);
        $data = $this->applyCategoryOptions($request, $data);

        DB::transaction(fn () => $eventQuestion->update($data));

        return to_route('admin.event-questions.index')->with('success', 'Question updated.');
    }

    public function destroy(EventRequirementQuestion $eventQuestion)
    {
        $eventQuestion->delete();

        return to_route('admin.event-questions.index')->with('success', 'Question deleted.');
    }

    private function applyCategoryOptions(StoreEventQuestionRequest $request, array $data): array
    {
        $categoryOptions = $request->input('category_options', []);
        if ($categoryOptions === []) {
            return $data;
        }

        $options = [];
        $images = [];
        $vendorValues = [];
        $optionMetadata = [];
        $existingMetadata = (array) ($data['option_metadata'] ?? []);
        $mappedValues = array_values($data['vendor_attribute_values'] ?? []);
        foreach ($categoryOptions as $index => $option) {
            $name = trim((string) ($option['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $options[] = $name;
            $upload = $request->file("category_options.{$index}.image");
            $images[] = $upload?->isValid()
                ? $upload->store('question-options', 'public')
                : (($option['existing_image'] ?? null) ?: null);
            $vendorValue = ($option['vendor_value'] ?? null) ?: ($mappedValues[$index] ?? null);
            $vendorValues[] = $vendorValue;
            $metadataKey = (string) ($vendorValue ?: $name);
            $details = (array) ($existingMetadata[$metadataKey] ?? $existingMetadata[$name] ?? []);
            $optionMetadata[$metadataKey] = array_merge($details, [
                'label' => $name,
                'subtitle' => trim((string) ($option['subtitle'] ?? '')),
                'icon' => trim((string) ($option['icon'] ?? '')) ?: 'fa-solid fa-star',
            ]);
        }

        $data['options'] = $options;
        $data['option_images'] = $images;
        $data['option_vendor_values'] = $vendorValues;
        $data['option_metadata'] = $optionMetadata;

        return $data;
    }
}
