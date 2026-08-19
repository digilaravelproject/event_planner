<?php

namespace App\Http\Controllers;

use App\Models\EventRequirementQuestion;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use App\Services\EventPlanningService;
use App\Services\PlanPdfService;
use App\Services\PlanPresentationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AiPlannerController extends Controller
{
    public function index(Request $request)
    {
        $orderedQuestions = EventRequirementQuestion::enabled()->get();
        $questions = $orderedQuestions->keyBy('question_code');
        $guestCount = max(10, min(5000, (int) $request->integer('guests', 150)));
        $plannerSteps = $this->plannerSteps($orderedQuestions);
        $stepNumbers = $plannerSteps->pluck('number', 'code')->all();

        $vendorPackages = DynamicVendor::query()
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->latest('id')
            ->get()
            ->flatMap(function (DynamicVendor $vendor): array {
                $offerings = data_get($vendor->vendor_json, 'offerings', []);
                if (empty($offerings) && ! empty(data_get($vendor->vendor_json, 'offering'))) {
                    $offerings = [data_get($vendor->vendor_json, 'offering')];
                }

                $attrs = collect(data_get($vendor->vendor_json, 'attributes', []))->keyBy('label');
                $images = array_merge(
                    (array) data_get($vendor->vendor_json, 'media.images', []),
                    $attrs->pluck('images')->flatten()->filter()->all()
                );
                $imageUrls = collect($images)->map(
                    fn (string $path): string => str_starts_with($path, 'http') ? $path : asset('storage/'.ltrim($path, '/'))
                )->values()->all();

                if (empty($offerings)) {
                    $rawLocations = $attrs->get('Supported Locations')['value'] ?? $attrs->get('Service Area')['value'] ?? '';
                    $locationsList = is_array($rawLocations) ? $rawLocations : array_map('trim', explode(',', (string) $rawLocations));
                    $rawTraditions = $attrs->get('Supported Traditions')['value'] ?? '';
                    $traditionsList = is_array($rawTraditions) ? $rawTraditions : array_map('trim', explode(',', (string) $rawTraditions));
                    $rawDecor = $attrs->get('Decoration Type')['value'] ?? $attrs->get('Decor Category')['value'] ?? $vendor->name;
                    $rawNote = $attrs->get('Package Details Note')['value'] ?? $vendor->vendor_json['identity']['short_description'] ?? 'Custom tailored wedding package.';

                    return [[
                        'id' => $vendor->id,
                        'name' => $vendor->name,
                        'category' => $vendor->category,
                        'min_capacity' => (int) (is_array($val = $attrs->get('Min Guest Capacity')['value'] ?? $attrs->get('Guest Capacity')['value'] ?? 50) ? ($val[0] ?? 50) : $val),
                        'max_capacity' => (int) (is_array($val = $attrs->get('Max Guest Capacity')['value'] ?? $attrs->get('Guest Capacity')['value'] ?? 1000) ? ($val[0] ?? 1000) : $val),
                        'min_budget' => (float) (is_array($val = $attrs->get('Min Budget Lakhs')['value'] ?? 2) ? ($val[0] ?? 2) : $val),
                        'max_budget' => (float) (is_array($val = $attrs->get('Max Budget Lakhs')['value'] ?? 50) ? ($val[0] ?? 50) : $val),
                        'locations' => array_values(array_filter($locationsList)),
                        'traditions' => array_values(array_filter($traditionsList)),
                        'decor_type' => is_array($rawDecor) ? implode(', ', $rawDecor) : (string) $rawDecor,
                        'note' => is_array($rawNote) ? implode(' ', $rawNote) : (string) $rawNote,
                        'images' => $imageUrls,
                    ]];
                }

                return array_map(function (array $offering) use ($vendor, $attrs, $imageUrls): array {
                    $rawLocations = data_get($offering, 'locations') ?? $attrs->get('Supported Locations')['value'] ?? $attrs->get('Service Area')['value'] ?? '';
                    $locationsList = is_array($rawLocations) ? $rawLocations : array_map('trim', explode(',', (string) $rawLocations));

                    $rawTraditions = data_get($offering, 'traditions') ?? $attrs->get('Supported Traditions')['value'] ?? '';
                    $traditionsList = is_array($rawTraditions) ? $rawTraditions : array_map('trim', explode(',', (string) $rawTraditions));

                    $decorCategory = data_get($offering, 'category') ?: $vendor->category;
                    $pkgName = data_get($offering, 'name') ?: $vendor->name;
                    $note = data_get($offering, 'notes') ?: ($vendor->vendor_json['identity']['short_description'] ?? 'Custom tailored wedding package.');

                    $vendorBrandName = data_get($vendor->vendor_json, 'identity.name') ?: data_get($vendor->vendor_json, 'name') ?: 'Vendor';

                    return [
                        'id' => $vendor->id,
                        'vendor_name' => $vendorBrandName,
                        'name' => $pkgName,
                        'category' => $decorCategory,
                        'min_capacity' => (int) (data_get($offering, 'min_capacity', 50)),
                        'max_capacity' => (int) (data_get($offering, 'max_capacity', 1000)),
                        'min_budget' => (float) (data_get($offering, 'min_budget', 5)),
                        'max_budget' => (float) (data_get($offering, 'max_budget', 50)),
                        'locations' => array_values(array_filter($locationsList)),
                        'traditions' => array_values(array_filter($traditionsList)),
                        'decor_type' => $pkgName,
                        'note' => is_array($note) ? implode(' ', $note) : (string) $note,
                        'images' => $imageUrls,
                        'food_packages' => data_get($vendor->vendor_json, 'food_packages', []),
                        'food_extras' => data_get($vendor->vendor_json, 'food_extras', []),
                    ];
                }, $offerings);
            })->values()->all();

        $cateringVendors = DynamicVendor::query()
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->get()
            ->filter(function (DynamicVendor $vendor): bool {
                return in_array($vendor->category, ['Catering', 'Venue', 'Hotel', 'Lawn', 'Decorator'], true);
            })
            ->map(function (DynamicVendor $vendor): array {
                $brandName = data_get($vendor->vendor_json, 'identity.name') ?: data_get($vendor->vendor_json, 'name') ?: $vendor->name;

                return [
                    'id' => $vendor->id,
                    'name' => $brandName,
                    'category' => $vendor->category,
                    'food_packages' => data_get($vendor->vendor_json, 'food_packages', []),
                    'food_extras' => data_get($vendor->vendor_json, 'food_extras', []),
                ];
            })->values()->all();

        return view('ai-planner.index', [
            'questions' => $questions,
            'plannerSteps' => $plannerSteps->values()->all(),
            'stepNumbers' => $stepNumbers,
            'vendorPackages' => $vendorPackages,
            'cateringVendors' => $cateringVendors,
            'plannerOptions' => $questions->map(fn (EventRequirementQuestion $question): array => [
                'question' => $question->question,
                'options' => $this->plannerOptions($question),
                'images' => $this->questionImages($question),
                'required' => $question->is_required,
            ])->all(),
            'initialGuestCount' => $guestCount,
            'category' => $this->selectedCategory($questions->get('event_category'), (string) $request->query('type', '')),
        ]);
    }

    public function generate(Request $request, EventPlanningService $planning)
    {
        $answers = (array) $request->input('answers', []);
        foreach ($answers as $answerKey => $value) {
            if (is_string($value) && str_starts_with(trim($value), '[')) {
                $answers[$answerKey] = json_decode($value, true) ?: [];
            }
        }
        if (! array_key_exists('event_category', $answers)) {
            $answers['event_category'] = (string) $request->input('category', 'wedding');
        }
        $request->merge(['answers' => $answers]);

        $questions = EventRequirementQuestion::enabled()->get();
        $categoryOptions = collect($questions->firstWhere('question_code', 'event_category')?->options ?? [])->map('strval')->filter()->values();
        $answerRules = [];
        foreach ($questions as $question) {
            $base = $question->is_required ? ['required'] : ['nullable'];
            $answerRules['answers.'.$question->question_code] = match ($question->question_type) {
                'number' => [...$base, 'numeric'],
                'checkbox', 'multi_select' => [...$base, 'array', 'max:100'],
                default => [...$base, 'string', 'max:2000'],
            };
        }

        $validated = $request->validate(array_merge($answerRules, [
            'category' => ['required', 'string', 'max:255', Rule::in($categoryOptions->prepend('wedding')->unique()->all())],
            'guest_count' => ['required', 'integer', 'min:10', 'max:5000'],
            'answers' => ['required', 'array'],
            'answers.wedding_budget' => ['required', 'numeric', 'min:1', 'max:500'],
            'answers.guest_capacity' => ['nullable', 'integer', 'min:10', 'max:5000'],
            'answers.wedding_tradition' => ['nullable', 'string', 'max:255'],
            'answers.decoration_type' => ['nullable', 'string', 'max:255'],
            'answers.venue_setting' => ['nullable', 'string', 'max:255'],
            'answers.food_type' => ['nullable', 'string', 'max:2000'],
            'answers.service_area' => ['nullable', 'array', 'max:50'],
            'answers.service_area.*' => ['string', 'max:255'],
            'answers.event_timeline' => ['nullable', 'string', 'max:255'],
            'answers.event_date' => ['nullable', 'date'],
            'answers.food_menu_items' => ['nullable', 'array', 'max:100'],
            'answers.food_menu_items.*.id' => ['required', 'string', 'max:255'],
            'answers.food_menu_items.*.title' => ['required', 'string', 'max:255'],
            'answers.food_menu_items.*.category' => ['required', 'string', 'max:100'],
            'answers.food_menu_items.*.cost' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'answers.selected_food_package' => ['nullable', 'array'],
            'answers.selected_food_extras' => ['nullable', 'array'],
            'answers.ceremonies' => ['nullable', 'array', 'max:50'],
            'answers.ceremonies.*' => ['string', 'max:255'],
        ]));
        if (! empty($validated['answers']['food_menu_items'])) {
            $validated['answers']['food_menu_items'] = $this->validatedFoodMenuItems($validated['answers']['food_menu_items']);
        }

        $request->session()->put('pending_event_plan', $validated);

        if (! $request->user()) {
            return redirect()->route('user.login')
                ->with('success', 'Your requirements are saved. Login or register to generate your AI plan.');
        }

        return $this->finishPending($request, $planning);
    }

    public function resume(Request $request, EventPlanningService $planning)
    {
        if (! $request->session()->has('pending_event_plan')) {
            return redirect()->route('user.plans.index');
        }

        return $this->finishPending($request, $planning);
    }

    public function history(Request $request)
    {
        $plans = $request->user()->eventPlans()
            ->whereNull('parent_plan_id')
            ->withCount('suggestions')
            ->latest()
            ->paginate(10);

        return view('user.plans.index', compact('plans'));
    }

    public function show(Request $request, UserEventPlan $plan, PlanPresentationService $presenter)
    {
        abort_unless($plan->user_id === $request->user()->id, 403);
        $plan->load(['suggestions', 'parent.suggestions']);
        $presentation = $presenter->present($plan);

        return view('ai-planner.summary', compact('plan', 'presentation'));
    }

    public function download(Request $request, UserEventPlan $plan, PlanPresentationService $presenter, PlanPdfService $pdf)
    {
        abort_unless($plan->user_id === $request->user()->id, 403);
        $plan->load('user');

        return response($pdf->render($plan, $presenter->present($plan)))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="wedding-plan-'.$plan->id.'.pdf"');
    }

    private function finishPending(Request $request, EventPlanningService $planning)
    {
        $requirements = $request->session()->pull('pending_event_plan');

        try {
            $plan = $planning->create($request->user(), $requirements);
        } catch (\Throwable $exception) {
            report($exception);
            $request->session()->put('pending_event_plan', $requirements);

            return redirect()->route('ai-planner', [
                'type' => 'wedding',
                'guests' => $requirements['guest_count'],
            ])->withErrors(['planner' => 'We could not save your plan. Please try again.']);
        }

        return redirect()->route('user.plans.show', $plan)
            ->with('success', 'Your AI wedding plan is ready.');
    }

    private function plannerOptions(EventRequirementQuestion $question): array
    {
        if ($question->question_code !== 'food_type') {
            return array_values($question->options ?? []);
        }

        $metadata = (array) ($question->option_metadata ?? []);
        $options = array_values($question->options ?: $question->vendor_attribute_values ?: []);
        $vendorValues = array_values($question->option_vendor_values ?: $question->vendor_attribute_values ?: $options);
        $images = $this->questionImages($question);

        return collect($options)->map(function ($option, int $index) use ($images, $metadata, $vendorValues): array {
            $title = (string) $option;
            $value = (string) ($vendorValues[$index] ?? $title);
            $details = (array) ($metadata[$value] ?? $metadata[$title] ?? []);

            return [
                'id' => $value,
                'title' => $title,
                'category' => (string) ($details['category'] ?? 'Menu Items'),
                'cost' => max(0, (float) ($details['cost'] ?? 0)),
                'image' => $images[$index] ?? null,
            ];
        })->values()->all();
    }

    private function plannerSteps($questions)
    {
        $renderers = [
            'wedding_budget' => 'budget',
            'guest_capacity' => 'guest',
            'service_area' => 'location',
            'wedding_tradition' => 'tradition',
            'decoration_type' => 'setting',
            'food_type' => 'food',
            'event_timeline' => 'timeline',
        ];
        $labels = [
            'event_category' => 'Event Category',
            'wedding_budget' => 'Budget Allocation',
            'guest_capacity' => 'Guest Capacity',
            'service_area' => 'Location & Vibe',
            'wedding_tradition' => 'Wedding Tradition',
            'decoration_type' => 'Decor & Venue Style',
            'food_type' => 'Food & Catering',
            'event_timeline' => 'Dates & Timeline',
        ];

        return $questions->values()->map(function (EventRequirementQuestion $question, int $index) use ($renderers, $labels): array {
            return [
                'number' => $index + 1,
                'code' => $question->question_code,
                'name' => $labels[$question->question_code] ?? Str::limit($question->question, 32),
                'question' => $question->question,
                'type' => $question->question_type,
                'placeholder' => $question->placeholder,
                'required' => $question->is_required,
                'renderer' => $renderers[$question->question_code] ?? 'generic',
                'options' => array_values($question->options ?? []),
                'images' => $this->questionImages($question),
                'option_details' => $this->questionOptionDetails($question),
            ];
        });
    }

    private function questionOptionDetails(EventRequirementQuestion $question): array
    {
        $options = array_values($question->options ?? []);
        $mappedValues = array_values($question->option_vendor_values ?: $question->vendor_attribute_values ?: []);
        $metadata = (array) ($question->option_metadata ?? []);
        $images = $this->questionImages($question);
        $defaultIcons = [
            'fa-solid fa-star',
            'fa-solid fa-heart',
            'fa-solid fa-crown',
            'fa-solid fa-leaf',
            'fa-solid fa-gem',
            'fa-solid fa-champagne-glasses',
            'fa-solid fa-music',
            'fa-solid fa-building',
        ];

        return collect($options)->map(function ($option, int $index) use ($defaultIcons, $images, $mappedValues, $metadata): array {
            $title = (string) $option;
            $metadataKey = (string) (($mappedValues[$index] ?? null) ?: $title);
            $details = (array) ($metadata[$metadataKey] ?? $metadata[$title] ?? []);

            return [
                'value' => $title,
                'title' => $title,
                'subtitle' => trim((string) ($details['subtitle'] ?? '')) ?: 'A personalized choice tailored to your event.',
                'icon' => trim((string) ($details['icon'] ?? '')) ?: $defaultIcons[$index % count($defaultIcons)],
                'image' => $images[$index] ?? null,
            ];
        })->values()->all();
    }

    private function questionImages(EventRequirementQuestion $question): array
    {
        $optionImages = array_values($question->option_images ?? []);
        $vendorImages = array_values($question->vendor_attribute_images ?? []);
        $count = max(count($question->options ?? []), count($optionImages), count($vendorImages));
        if ($count === 0) {
            return [];
        }

        return collect(range(0, $count - 1))->map(function (int $index) use ($optionImages, $vendorImages): ?string {
            $path = ($optionImages[$index] ?? null) ?: ($vendorImages[$index] ?? null);

            return $path ? (str_starts_with($path, 'http') ? $path : asset('storage/'.ltrim($path, '/'))) : null;
        })->values()->all();
    }

    private function selectedCategory(?EventRequirementQuestion $question, string $requested): string
    {
        $options = collect($question?->options ?? [])->map('strval')->filter()->values();
        if ($requested !== '' && ($requested === 'wedding' || $options->contains($requested))) {
            return $requested;
        }

        return (string) ($options->first() ?? 'wedding');
    }

    private function validatedFoodMenuItems(array $selectedItems): array
    {
        $question = EventRequirementQuestion::enabled()->where('question_code', 'food_type')->first();
        $metadata = (array) ($question?->option_metadata ?? []);
        $options = collect($question?->options ?? [])->map('strval')->values();
        $mappedValues = collect($question?->option_vendor_values ?: $question?->vendor_attribute_values ?: [])->values();
        $values = $options->map(fn (string $option, int $index): string => (string) (($mappedValues[$index] ?? null) ?: $option));
        $labels = $values->mapWithKeys(fn (string $value, int $index): array => [$value => $options[$index] ?? $value]);
        $selectedIds = collect($selectedItems)->pluck('id')->map('strval')->unique()->values();

        if (! $question || $selectedIds->diff($values)->isNotEmpty()) {
            throw ValidationException::withMessages(['answers.food_menu_items' => 'Select menu items from the available food question.']);
        }

        return $selectedIds->map(function (string $id) use ($labels, $metadata): array {
            $details = (array) ($metadata[$id] ?? []);

            return [
                'id' => $id,
                'title' => $labels[$id] ?? $id,
                'category' => (string) ($details['category'] ?? 'Menu Items'),
                'cost' => max(0, (float) ($details['cost'] ?? 0)),
            ];
        })->all();
    }
}
