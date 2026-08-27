<?php

namespace App\Services;

use App\Models\AiSetting;
use App\Models\EventRequirementQuestion;
use App\Models\User;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EventPlanningService
{
    public function __construct(private readonly OpenRouterService $openRouter) {}

    public function create(User $user, array $requirements): UserEventPlan
    {
        $answers = Arr::wrap($requirements['answers'] ?? []);
        $guestCount = max(1, min(5000, (int) ($requirements['guest_count'] ?? 150)));
        $category = trim((string) ($requirements['category'] ?? 'wedding')) ?: 'wedding';
        $vendors = $this->vendorSnapshot($answers);
        $prompt = $this->prompt($category, $guestCount, $answers, $vendors);
        $model = (string) AiSetting::getValue('openrouter_model', 'openrouter/auto');

        $plan = UserEventPlan::create([
            'user_id' => $user->id,
            'title' => 'Wedding Plan · '.now()->format('d M Y'),
            'category' => $category,
            'guest_count' => $guestCount,
            'answers' => $answers,
            'requirement_prompt' => $prompt,
            'vendor_snapshot' => $vendors,
            'model' => $model,
            'status' => 'generating',
        ]);

        try {
            $response = $this->openRouter->chat([
                ['role' => 'user', 'content' => $prompt],
            ], $model);
            $summary = $this->parseSummary((string) data_get($response, 'choices.0.message.content', ''), $answers, $guestCount, $vendors);
        } catch (\Throwable $exception) {
            report($exception);
            $summary = $this->fallbackSummary($answers, $guestCount, $vendors);
            $plan->error_message = Str::limit($exception->getMessage(), 1000);
        }

        $summary = $this->applySelectedFoodCosting($summary, $answers, $guestCount);
        $summary['display_content'] = $this->displayContent($summary, $answers, $guestCount, $category);

        $plan->fill([
            'title' => (string) ($summary['title'] ?? $plan->title),
            'summary' => $summary,
            'total_cost' => (float) $summary['total_cost'],
            'status' => 'completed',
        ])->save();

        $this->createSuggestions($plan);

        return $plan->fresh(['suggestions']);
    }

    public function update(UserEventPlan $plan, array $requirements): UserEventPlan
    {
        $answers = Arr::wrap($requirements['answers'] ?? []);
        $guestCount = max(1, min(5000, (int) ($requirements['guest_count'] ?? 150)));
        $category = trim((string) ($requirements['category'] ?? 'wedding')) ?: 'wedding';
        $vendors = $this->vendorSnapshot($answers);
        $prompt = $this->prompt($category, $guestCount, $answers, $vendors);
        $model = (string) AiSetting::getValue('openrouter_model', 'openrouter/auto');
        $errorMessage = null;

        try {
            $response = $this->openRouter->chat([
                ['role' => 'user', 'content' => $prompt],
            ], $model);
            $summary = $this->parseSummary((string) data_get($response, 'choices.0.message.content', ''), $answers, $guestCount, $vendors);
        } catch (\Throwable $exception) {
            report($exception);
            $summary = $this->fallbackSummary($answers, $guestCount, $vendors);
            $errorMessage = Str::limit($exception->getMessage(), 1000);
        }

        $summary = $this->applySelectedFoodCosting($summary, $answers, $guestCount);
        $summary['display_content'] = $this->displayContent($summary, $answers, $guestCount, $category);

        return DB::transaction(function () use ($plan, $answers, $guestCount, $category, $vendors, $prompt, $model, $summary, $errorMessage): UserEventPlan {
            $plan->suggestions()->delete();
            $plan->fill([
                'title' => (string) ($summary['title'] ?? $plan->title),
                'category' => $category,
                'guest_count' => $guestCount,
                'answers' => $answers,
                'requirement_prompt' => $prompt,
                'vendor_snapshot' => $vendors,
                'summary' => $summary,
                'total_cost' => (float) $summary['total_cost'],
                'model' => $model,
                'status' => 'completed',
                'error_message' => $errorMessage,
            ])->save();
            $this->createSuggestions($plan);

            return $plan->fresh(['suggestions']);
        });
    }

    private function vendorSnapshot(array $answers): array
    {
        $criteria = $this->mappedVendorCriteria($answers);

        return DynamicVendor::query()
            ->whereRaw('LOWER(status) = ?', ['active'])
            ->latest('id')
            ->limit(200)
            ->get()
            ->map(function (DynamicVendor $vendor) use ($criteria): array {
                $attributes = collect((array) data_get($vendor->vendor_json, 'attributes', []))
                    ->filter(fn ($attribute): bool => is_array($attribute) && trim((string) ($attribute['key'] ?? '')) !== '')
                    ->mapWithKeys(fn (array $attribute): array => [
                        (string) $attribute['key'] => $attribute['value'] ?? null,
                    ])
                    ->filter(fn ($value) => $value !== null && $value !== '' && $value !== [])
                    ->take(30)
                    ->all();
                $matches = collect($criteria)->filter(
                    fn (array $values, string $key): bool => $this->attributeMatches($attributes[$key] ?? null, $values)
                )->keys()->values()->all();

                return [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'category' => $vendor->category,
                    'attributes' => $attributes,
                    'matched_requirement_keys' => $matches,
                    'match_score' => count($matches),
                ];
            })
            ->sortByDesc('match_score')
            ->values()
            ->all();
    }

    private function mappedVendorCriteria(array $answers): array
    {
        return EventRequirementQuestion::enabled()
            ->whereNotNull('vendor_attribute_key')
            ->get()
            ->mapWithKeys(function (EventRequirementQuestion $question) use ($answers): array {
                $answer = $answers[$question->question_code] ?? null;
                if ($answer === null || $answer === '' || $answer === []) {
                    return [];
                }

                $selected = collect(is_array($answer) ? $answer : [$answer])->map('strval');
                $options = collect($question->options ?? [])->map('strval');
                $mapped = collect($question->option_vendor_values ?? $question->vendor_attribute_values ?? [])->map('strval');
                $values = $selected->map(function (string $value) use ($options, $mapped): string {
                    $index = $options->search($value, true);

                    return $index !== false ? (string) ($mapped[$index] ?? $value) : $value;
                })->filter()->unique()->values()->all();

                return $values === [] ? [] : [$question->vendor_attribute_key => $values];
            })->all();
    }

    private function attributeMatches(mixed $attributeValue, array $selectedValues): bool
    {
        $available = collect(is_array($attributeValue) ? $attributeValue : [$attributeValue])
            ->map(fn ($value): string => mb_strtolower(trim((string) $value)))
            ->filter();

        return collect($selectedValues)
            ->map(fn ($value): string => mb_strtolower(trim((string) $value)))
            ->contains(fn (string $value): bool => $available->contains($value));
    }

    private function prompt(string $category, int $guestCount, array $answers, array $vendors): string
    {
        return 'Create a practical Indian wedding plan grounded only in the supplied active vendor data. '
            .'Do not invent vendor IDs or claim availability that is not present. Return JSON only, without markdown, using this exact shape: '
            .'{"title":string,"overview":string,"total_cost":number,"costing":[{"category":string,"amount":number,"percentage":number,"summary":string,"vendor_ids":number[],"attributes":[{"name":string,"value":string,"cost":number}]}],'
            .'"recommendations":[{"vendor_id":number,"name":string,"category":string,"reason":string,"estimated_cost":number}],"notes":string[]}. '
            .'All monetary numbers must be INR rupees. Keep each costing item small and understandable, include 3 to 6 attribute-level costs in every costing item, and make total_cost equal the sum of costing amounts. '
            .'When answers.food_menu_items is present, it is the exclusive food menu selected by the user. Each configured cost is INR per guest: include only those dishes in catering attributes and calculate each dish as cost multiplied by guest_count. '
            .'Requirements: '.json_encode(['category' => $category, 'guest_count' => $guestCount, 'answers' => $answers], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'. '
            .'Active vendors: '.json_encode($vendors, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'.';
    }

    private function parseSummary(string $content, array $answers, int $guestCount, array $vendors): array
    {
        $content = trim(preg_replace('/^```(?:json)?|```$/mi', '', trim($content)) ?? '');
        $decoded = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($decoded) || ! is_array($decoded['costing'] ?? null) || $decoded['costing'] === []) {
            throw new \RuntimeException('OpenRouter returned an invalid planning summary.');
        }

        $allowedVendors = collect($vendors)->keyBy('id');
        $costing = collect($decoded['costing'])->map(function ($item): array {
            return [
                'category' => Str::limit((string) ($item['category'] ?? 'Wedding service'), 100),
                'amount' => max(0, round((float) ($item['amount'] ?? 0), 2)),
                'percentage' => max(0, min(100, round((float) ($item['percentage'] ?? 0), 1))),
                'summary' => Str::limit((string) ($item['summary'] ?? ''), 300),
                'vendor_ids' => collect($item['vendor_ids'] ?? [])->map(fn ($id) => (int) $id)->filter()->values()->all(),
                'attributes' => collect($item['attributes'] ?? [])->map(fn ($attribute): array => [
                    'name' => Str::limit((string) ($attribute['name'] ?? 'Service item'), 100),
                    'value' => Str::limit((string) ($attribute['value'] ?? ''), 200),
                    'cost' => max(0, round((float) ($attribute['cost'] ?? 0), 2)),
                ])->filter(fn (array $attribute): bool => $attribute['cost'] > 0)->take(8)->values()->all(),
            ];
        })->filter(fn (array $item): bool => $item['amount'] > 0)->values();

        if ($costing->isEmpty()) {
            return $this->fallbackSummary($answers, $guestCount, $vendors);
        }

        $total = round((float) $costing->sum('amount'), 2);
        $recommendations = collect($decoded['recommendations'] ?? [])->map(function ($item) use ($allowedVendors): ?array {
            $vendor = $allowedVendors->get((int) ($item['vendor_id'] ?? 0));
            if (! $vendor) {
                return null;
            }

            return [
                'vendor_id' => $vendor['id'],
                'name' => $vendor['name'],
                'category' => $vendor['category'],
                'reason' => Str::limit((string) ($item['reason'] ?? 'Matches your selected requirements.'), 300),
                'estimated_cost' => max(0, round((float) ($item['estimated_cost'] ?? 0), 2)),
            ];
        })->filter()->take(12)->values()->all();

        return [
            'title' => Str::limit((string) ($decoded['title'] ?? 'Your Custom Wedding Plan'), 150),
            'overview' => Str::limit((string) ($decoded['overview'] ?? 'A wedding plan tailored to your selected requirements.'), 1000),
            'total_cost' => $total,
            'costing' => $costing->all(),
            'recommendations' => $recommendations,
            'notes' => collect($decoded['notes'] ?? [])->map(fn ($note) => Str::limit((string) $note, 300))->take(8)->values()->all(),
        ];
    }

    private function fallbackSummary(array $answers, int $guestCount, array $vendors): array
    {
        $budgetLakh = max(3, min(500, (float) ($answers['wedding_budget'] ?? $answers['budget'] ?? 25)));
        $total = round($budgetLakh * 100000, 2);
        $allocations = [
            ['Venue & Stay', .35, 'Venue, seating, hospitality and essential facilities.'],
            ['Catering & Service', .30, 'Menu and service allocation for '.$guestCount.' guests.'],
            ['Decor & Styling', .15, 'Mandap, floral, lighting and styling requirements.'],
            ['Photography & Entertainment', .12, 'Photography, film and entertainment coverage.'],
            ['Planning Contingency', .08, 'Coordination, transport and contingency reserve.'],
        ];
        $costing = collect($allocations)->map(function (array $row) use ($total, $vendors): array {
            $matchedVendors = $this->vendorsForCategory($vendors, $row[0])->take(3);

            return [
                'category' => $row[0],
                'amount' => round($total * $row[1], 2),
                'percentage' => $row[1] * 100,
                'summary' => $row[2],
                'vendor_ids' => $matchedVendors->pluck('id')->all(),
                'attributes' => [],
            ];
        })->all();
        $usedVendorIds = collect($costing)->pluck('vendor_ids')->flatten()->unique();
        $recommendations = collect($vendors)->whereIn('id', $usedVendorIds)->take(12)->map(fn (array $vendor): array => [
            'vendor_id' => $vendor['id'],
            'name' => $vendor['name'],
            'category' => $vendor['category'],
            'reason' => 'Active vendor with attributes relevant to this wedding plan.',
            'estimated_cost' => 0,
        ])->values()->all();

        return [
            'title' => 'Your Custom Wedding Breakdown',
            'overview' => 'A balanced plan for '.$guestCount.' guests based on your selected budget and preferences.',
            'total_cost' => $total,
            'costing' => $costing,
            'recommendations' => $recommendations,
            'notes' => ['Confirm final quotations and availability directly with shortlisted vendors.'],
        ];
    }

    private function vendorsForCategory(array $vendors, string $category)
    {
        $category = strtolower($category);
        $groups = [
            ['venue', 'stay', 'hotel', 'banquet', 'resort'],
            ['cater', 'food', 'menu'],
            ['decor', 'style', 'florist', 'flower', 'mandap'],
            ['photo', 'media', 'video', 'entertainment', 'dj', 'sound'],
            ['planning', 'contingency', 'transport', 'travel', 'coordination'],
        ];
        $selectedGroup = collect($groups)->first(fn (array $group): bool => collect($group)->contains(fn (string $word): bool => str_contains($category, $word)));

        return collect($vendors)->filter(function (array $vendor) use ($category, $selectedGroup): bool {
            $vendorText = strtolower((string) ($vendor['category'] ?? '').' '.(string) ($vendor['name'] ?? ''));

            return $selectedGroup
                ? collect($selectedGroup)->contains(fn (string $word): bool => str_contains($vendorText, $word))
                : str_contains($vendorText, $category);
        })->sortBy(function (array $vendor) use ($selectedGroup): int {
            if (! $selectedGroup) {
                return 0;
            }
            $vendorText = strtolower((string) ($vendor['category'] ?? '').' '.(string) ($vendor['name'] ?? ''));

            return (int) (collect($selectedGroup)->search(fn (string $word): bool => str_contains($vendorText, $word)) ?: 0);
        })->values();
    }

    private function createSuggestions(UserEventPlan $plan): void
    {
        foreach ($this->suggestionDefinitions() as $index => [$title, $factor, $tier]) {
            $summary = $plan->summary;
            $summary['title'] = $title;
            $change = (int) round(abs(1 - $factor) * 100);
            $summary['overview'] = $factor < 1
                ? $change.'% below the original plan while retaining the saved wedding requirements.'
                : $change.'% above the original plan with additional budget across the saved service categories.';
            $summary['costing'] = collect($summary['costing'])->map(function (array $item) use ($factor): array {
                $item['amount'] = round(((float) $item['amount']) * $factor, 2);

                return $item;
            })->all();
            $summary['total_cost'] = round(collect($summary['costing'])->sum('amount'), 2);
            $summary['comparison'] = [
                'tier' => $tier,
                'change_label' => $change.'% '.($factor < 1 ? 'lower investment' : 'higher investment'),
                'requirements_label' => count($plan->answers ?? []).' saved selections retained',
                'costing_label' => count($summary['costing']).' service costs recalculated',
                'image' => $index % 2 === 0 ? 'images/planner/value-wedding-plan.webp' : 'images/planner/premium-wedding-plan.webp',
            ];
            $summary['display_content'] = $this->displayContent($summary, $plan->answers ?? [], $plan->guest_count, $plan->category);

            UserEventPlan::create([
                'user_id' => $plan->user_id,
                'parent_plan_id' => $plan->id,
                'title' => $title,
                'category' => $plan->category,
                'guest_count' => $plan->guest_count,
                'answers' => $plan->answers,
                'requirement_prompt' => $plan->requirement_prompt,
                'vendor_snapshot' => $plan->vendor_snapshot,
                'summary' => $summary,
                'total_cost' => $summary['total_cost'],
                'model' => $plan->model,
                'status' => 'completed',
            ]);
        }
    }

    private function suggestionDefinitions(): array
    {
        return [
            ['Essential Wedding Plan', .75, 'Essential option'],
            ['Smart Value Wedding Plan', .85, 'Smart value option'],
            ['Nearby Value Plan', .90, 'Value option'],
            ['Nearby Premium Plan', 1.10, 'Premium option'],
            ['Signature Wedding Plan', 1.15, 'Signature option'],
            ['Luxury Wedding Plan', 1.25, 'Luxury option'],
        ];
    }

    private function displayContent(array $summary, array $answers, int $guestCount, string $category): array
    {
        $serviceCount = count($summary['costing'] ?? []);
        $answerCount = count($answers);
        $categoryName = Str::headline($category);

        return [
            'brand_label' => 'Shaadi Sense AI',
            'sidebar_title' => (string) ($summary['title'] ?? $categoryName.' plan'),
            'sidebar_description' => $guestCount.' guests · '.$serviceCount.' costed services · '.$answerCount.' saved requirements',
            'estimated_total_label' => 'Estimated plan total',
            'guests_label' => 'Guests',
            'services_label' => 'Costed services',
            'download_label' => 'Download plan PDF',
            'new_plan_label' => 'Generate new plan',
            'dashboard_label' => 'User dashboard',
            'hero_badge' => $guestCount.'-guest '.$categoryName.' plan',
            'selection_eyebrow' => $answerCount.' saved requirements',
            'selection_title' => 'Selections used for '.$summary['title'],
            'costing_eyebrow' => $serviceCount.' costed service categories',
            'costing_title' => 'Detailed costing for '.$summary['title'],
            'costing_description' => 'Every amount below comes from the costing saved with this generated plan.',
            'category_total_label' => 'Saved category total',
            'comparison_eyebrow' => 'Saved plan alternatives',
            'comparison_title' => 'More budgets for the same requirements',
            'comparison_description' => 'Compare saved alternatives generated from this plan and its recorded selections.',
            'comparison_count_label' => 'saved alternatives',
            'comparison_costing_label' => 'Open saved costing',
            'comparison_view_label' => 'View plan →',
        ];
    }

    private function applySelectedFoodCosting(array $summary, array $answers, int $guestCount): array
    {
        $menuItems = $answers['food_menu_items'] ?? [];
        if (is_string($menuItems)) {
            $menuItems = json_decode($menuItems, true) ?: [];
        }

        $menuItems = collect(is_array($menuItems) ? $menuItems : [])->filter(fn ($item): bool => is_array($item) && trim((string) ($item['title'] ?? '')) !== '')->map(function (array $item) use ($guestCount): array {
            $pricePerGuest = max(0, (float) ($item['cost'] ?? 0));
            $vendorName = trim((string) ($item['vendor_name'] ?? ''));

            return [
                'name' => Str::limit((string) $item['title'], 100),
                'value' => Str::limit(($vendorName !== '' ? $vendorName.' · ' : '').(string) ($item['category'] ?? 'Menu Items').' at Rs. '.number_format($pricePerGuest, 2).' per guest for '.$guestCount.' guests', 200),
                'cost' => round($pricePerGuest * $guestCount, 2),
                'vendor_id' => isset($item['vendor_id']) ? (int) $item['vendor_id'] : null,
            ];
        })->values();

        if ($menuItems->isEmpty()) {
            return $summary;
        }

        $costing = collect($summary['costing'] ?? [])->values();
        $cateringIndex = $costing->search(fn (array $item): bool => str_contains(strtolower((string) ($item['category'] ?? '')), 'cater') || str_contains(strtolower((string) ($item['category'] ?? '')), 'food'));
        $menuTotal = round((float) $menuItems->sum('cost'), 2);
        $catering = $cateringIndex === false ? [
            'category' => 'Food & Catering',
            'vendor_ids' => [],
        ] : $costing->get($cateringIndex);
        $catering['amount'] = $menuTotal;
        $catering['summary'] = 'Your selected food menu for '.number_format($guestCount).' guests.';
        $catering['vendor_ids'] = $menuItems->pluck('vendor_id')->filter()->unique()->values()->all();
        $catering['attributes'] = $menuItems->map(function (array $item): array {
            unset($item['vendor_id']);

            return $item;
        })->all();

        if ($cateringIndex === false) {
            $costing->push($catering);
        } else {
            $costing->put($cateringIndex, $catering);
        }

        $total = round((float) $costing->sum(fn (array $item): float => (float) ($item['amount'] ?? 0)), 2);
        $summary['costing'] = $costing->map(function (array $item) use ($total): array {
            $item['percentage'] = $total > 0 ? round(((float) ($item['amount'] ?? 0) / $total) * 100, 1) : 0;

            return $item;
        })->values()->all();
        $summary['total_cost'] = $total;

        return $summary;
    }
}
