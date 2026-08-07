<?php

namespace App\Http\Controllers;

use App\Models\EventRequirementQuestion;
use App\Models\UserEventPlan;
use App\Services\EventPlanningService;
use App\Services\PlanPdfService;
use App\Services\PlanPresentationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AiPlannerController extends Controller
{
    public function index(Request $request)
    {
        $questions = EventRequirementQuestion::enabled()->get()->keyBy('question_code');
        $guestCount = max(10, min(5000, (int) $request->integer('guests', 150)));

        return view('ai-planner.index', [
            'questions' => $questions,
            'plannerOptions' => $questions->map(fn (EventRequirementQuestion $question): array => [
                'question' => $question->question,
                'options' => $this->plannerOptions($question),
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
        $answers = (array) $request->input('answers', []);
        foreach (['food_menu_items', 'ceremonies'] as $answerKey) {
            $value = $answers[$answerKey] ?? null;
            if (is_string($value) && str_starts_with(trim($value), '[')) {
                $answers[$answerKey] = json_decode($value, true) ?: [];
            }
        }
        $request->merge(['answers' => $answers]);

        $validated = $request->validate([
            'category' => ['required', 'in:wedding'],
            'guest_count' => ['required', 'integer', 'min:10', 'max:5000'],
            'answers' => ['required', 'array'],
            'answers.wedding_budget' => ['required', 'numeric', 'min:1', 'max:500'],
            'answers.guest_capacity' => ['nullable', 'integer', 'min:10', 'max:5000'],
            'answers.wedding_tradition' => ['nullable', 'string', 'max:255'],
            'answers.decoration_type' => ['nullable', 'string', 'max:255'],
            'answers.venue_setting' => ['nullable', 'string', 'max:255'],
            'answers.food_type' => ['nullable', 'string', 'max:2000'],
            'answers.service_area' => ['nullable', 'string', 'max:255'],
            'answers.event_timeline' => ['nullable', 'string', 'max:255'],
            'answers.food_menu_items' => ['nullable', 'array', 'max:100'],
            'answers.food_menu_items.*.id' => ['required', 'string', 'max:255'],
            'answers.food_menu_items.*.title' => ['required', 'string', 'max:255'],
            'answers.food_menu_items.*.category' => ['required', 'string', 'max:100'],
            'answers.food_menu_items.*.cost' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'answers.ceremonies' => ['nullable', 'array', 'max:50'],
            'answers.ceremonies.*' => ['string', 'max:255'],
        ]);
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

        $values = $question->vendor_attribute_values ?: $question->options ?: [];

        return collect($values)->map(function ($value, int $index) use ($question, $metadata): array {
            $value = (string) $value;
            $details = (array) ($metadata[$value] ?? []);

            return [
                'id' => $value,
                'title' => (string) ($question->options[$index] ?? $details['label'] ?? $value),
                'category' => (string) ($details['category'] ?? 'Menu Items'),
                'cost' => max(0, (float) ($details['cost'] ?? 0)),
            ];
        })->values()->all();
    }

    private function validatedFoodMenuItems(array $selectedItems): array
    {
        $question = EventRequirementQuestion::enabled()->where('question_code', 'food_type')->first();
        $metadata = (array) ($question?->option_metadata ?? []);
        $values = collect($question?->vendor_attribute_values ?? [])->map('strval')->values();
        $labels = $values->mapWithKeys(fn (string $value, int $index): array => [$value => (string) ($question->options[$index] ?? $value)]);
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
