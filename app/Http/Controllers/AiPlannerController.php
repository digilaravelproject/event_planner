<?php

namespace App\Http\Controllers;

use App\Models\EventRequirementQuestion;
use App\Models\UserEventPlan;
use App\Services\EventPlanningService;
use App\Services\PlanPdfService;
use App\Services\PlanPresentationService;
use Illuminate\Http\Request;

class AiPlannerController extends Controller
{
    public function index(Request $request)
    {
        $questions = EventRequirementQuestion::enabled()->get()->keyBy('question_code');
        $guestCount = max(10, min(5000, (int) $request->integer('guests', 150)));

        $vendorPackages = \App\Modules\DynamicVendors\Models\DynamicVendor::query()
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->whereIn('vendor_json->identity->category', ['Venue', 'Decorator'])
            ->latest('id')
            ->get()
            ->flatMap(function (\App\Modules\DynamicVendors\Models\DynamicVendor $vendor): array {
                $offerings = data_get($vendor->vendor_json, 'offerings', []);
                if (empty($offerings) && !empty(data_get($vendor->vendor_json, 'offering'))) {
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

                    return [
                        'id' => $vendor->id,
                        'name' => $pkgName,
                        'category' => $decorCategory,
                        'min_capacity' => (int) (data_get($offering, 'min_capacity', 50)),
                        'max_capacity' => (int) (data_get($offering, 'max_capacity', 1000)),
                        'min_budget' => (float) (data_get($offering, 'min_budget', 5)),
                        'max_budget' => (float) (data_get($offering, 'max_budget', 50)),
                        'locations' => array_values(array_filter($locationsList)),
                        'traditions' => array_values(array_filter($traditionsList)),
                        'decor_type' => $decorCategory,
                        'note' => $note,
                        'images' => $imageUrls,
                    ];
                }, $offerings);
            })->values()->all();

        return view('ai-planner.index', [
            'questions' => $questions,
            'vendorPackages' => $vendorPackages,
            'plannerOptions' => $questions->map(fn (EventRequirementQuestion $question): array => [
                'question' => $question->question,
                'options' => array_values($question->options ?? []),
                'images' => collect($question->vendor_attribute_images ?? [])->map(
                    fn (string $path): string => str_starts_with($path, 'http') ? $path : asset('storage/'.ltrim($path, '/'))
                )->values()->all(),
                'required' => $question->is_required,
            ])->all(),
            'initialGuestCount' => $guestCount,
            'category' => 'wedding',
        ]);
    }

    public function generate(Request $request, EventPlanningService $planning)
    {
        $validated = $request->validate([
            'category' => ['required', 'in:wedding'],
            'guest_count' => ['required', 'integer', 'min:10', 'max:5000'],
            'answers' => ['required', 'array'],
            'answers.wedding_budget' => ['required', 'numeric', 'min:1', 'max:500'],
        ]);

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
        $plan->load(['suggestions', 'parent']);
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
}
