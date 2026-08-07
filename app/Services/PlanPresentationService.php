<?php

namespace App\Services;

use App\Models\EventRequirementQuestion;
use App\Models\UserEventPlan;
use Illuminate\Support\Str;

class PlanPresentationService
{
    public function present(UserEventPlan $plan): array
    {
        $summary = $plan->summary ?? [];
        $vendors = collect($plan->vendor_snapshot ?? [])->keyBy('id');
        $costing = collect($summary['costing'] ?? [])->map(function (array $item) use ($vendors): array {
            $amount = (float) ($item['amount'] ?? 0);
            $attributes = collect($item['attributes'] ?? [])->map(fn ($attribute): array => [
                'name' => $this->cleanText((string) ($attribute['name'] ?? 'Service item')),
                'value' => $this->cleanText((string) ($attribute['value'] ?? '')),
                'cost' => max(0, (float) ($attribute['cost'] ?? 0)),
            ])->filter(fn (array $attribute): bool => $attribute['name'] !== '')->values();

            if ($attributes->isEmpty()) {
                $attributes = collect($this->defaultBreakdown((string) ($item['category'] ?? 'Service'), $amount));
            } elseif (($attributeTotal = (float) $attributes->sum('cost')) > 0 && abs($attributeTotal - $amount) > 0.01) {
                $attributes = $attributes->map(function (array $attribute) use ($amount, $attributeTotal): array {
                    $attribute['cost'] = round($amount * ((float) $attribute['cost'] / $attributeTotal), 2);

                    return $attribute;
                });
            }

            $matchedVendors = collect($item['vendor_ids'] ?? [])->map(fn ($id) => $vendors->get((int) $id))->filter()->values()->all();

            return array_merge($item, [
                'category' => $this->cleanText((string) ($item['category'] ?? 'Service')),
                'summary' => $this->cleanText((string) ($item['summary'] ?? '')),
                'amount' => $amount,
                'attributes' => $attributes->all(),
                'vendors' => $matchedVendors,
            ]);
        })->values()->all();

        $recommendationSource = collect($summary['recommendations'] ?? []);
        if ($recommendationSource->isEmpty()) {
            $recommendationSource = $vendors->values()->take(8)->map(fn (array $vendor): array => [
                'vendor_id' => $vendor['id'],
                'name' => $vendor['name'],
                'category' => $vendor['category'],
                'reason' => 'Active vendor retained in the saved plan snapshot for requirement comparison.',
                'estimated_cost' => 0,
            ]);
        }

        $recommendations = $recommendationSource->map(function (array $recommendation) use ($vendors, $costing): array {
            $vendor = $vendors->get((int) ($recommendation['vendor_id'] ?? 0), []);
            $attributes = collect((array) ($vendor['attributes'] ?? []))->map(function ($value, $key): array {
                return [
                    'name' => Str::of((string) $key)->replace('_', ' ')->title()->toString(),
                    'value' => $this->displayValue($value),
                ];
            })->filter(fn (array $attribute): bool => $attribute['value'] !== '')->take(8)->values();

            $estimated = (float) ($recommendation['estimated_cost'] ?? 0);
            if ($estimated <= 0) {
                $category = strtolower((string) ($recommendation['category'] ?? ''));
                $matchingCost = collect($costing)->first(fn (array $item) => $category !== '' && str_contains(strtolower((string) ($item['category'] ?? '')), $category));
                $estimated = (float) data_get($matchingCost, 'amount', 0);
            }
            $attributeCount = max(1, $attributes->count());

            return array_merge($recommendation, [
                'name' => (string) ($vendor['name'] ?? $recommendation['name'] ?? 'Matched vendor'),
                'category' => (string) ($vendor['category'] ?? $recommendation['category'] ?? 'Vendor'),
                'estimated_cost' => $estimated,
                'attributes' => $attributes->map(fn (array $attribute): array => $attribute + [
                    'cost' => round($estimated / $attributeCount, 2),
                ])->all(),
            ]);
        })->values()->all();

        $answers = (array) ($plan->answers ?? []);
        $displayAnswers = $answers;
        if (! empty($displayAnswers['food_menu_items'])) {
            unset($displayAnswers['food_type']);
        }
        $questionLabels = EventRequirementQuestion::query()->whereIn('question_code', array_keys($displayAnswers))->pluck('question', 'question_code');
        $fallbackLabels = [
            'ceremonies' => 'Which ceremonies would you like to include?',
            'venue_setting' => 'What venue setting did you select?',
            'food_menu_items' => 'Which food menu items did you select?',
        ];
        $answerDetails = collect($displayAnswers)->map(function ($value, $key) use ($questionLabels, $fallbackLabels): array {
            return [
                'code' => (string) $key,
                'question' => $this->cleanText((string) ($questionLabels[$key] ?? $fallbackLabels[$key] ?? Str::of((string) $key)->replace('_', ' ')->title())),
                'answer' => $this->displayAnswerValue($value, (string) $key),
            ];
        })->filter(fn (array $answer): bool => $answer['answer'] !== '')->values()->all();

        return [
            'title' => $this->cleanText((string) ($summary['title'] ?? $plan->title)),
            'overview' => $this->cleanText((string) ($summary['overview'] ?? 'Your personalized wedding plan.')),
            'total_cost' => (float) $plan->total_cost,
            'costing' => $costing,
            'recommendations' => $recommendations,
            'notes' => collect($summary['notes'] ?? [])->map(fn ($note) => $this->cleanText((string) $note))->all(),
            'answers' => $answers,
            'answer_details' => $answerDetails,
            'content' => array_merge($this->defaultDisplayContent($summary, $answers, $plan), (array) ($summary['display_content'] ?? [])),
            'comparison' => (array) ($summary['comparison'] ?? []),
        ];
    }

    private function defaultBreakdown(string $category, float $amount): array
    {
        $key = strtolower($category);
        $parts = match (true) {
            str_contains($key, 'venue') => [['Venue rental', .55], ['Guest facilities and seating', .20], ['Service staff', .15], ['Utilities and contingency', .10]],
            str_contains($key, 'cater') || str_contains($key, 'food') => [['Menu and ingredients', .62], ['Kitchen and serving team', .18], ['Live counters and presentation', .12], ['Service contingency', .08]],
            str_contains($key, 'decor') || str_contains($key, 'style') => [['Mandap or stage structure', .38], ['Flowers and fabrics', .27], ['Lighting and installation', .22], ['Transport and dismantling', .13]],
            str_contains($key, 'photo') || str_contains($key, 'media') => [['Photography team', .36], ['Cinematography', .32], ['Editing and album', .20], ['Equipment and travel', .12]],
            default => [['Core service', .60], ['Staff and coordination', .25], ['Logistics and reserve', .15]],
        };

        return collect($parts)->map(fn (array $part): array => [
            'name' => $part[0],
            'value' => 'Indicative allocation',
            'cost' => round($amount * $part[1], 2),
        ])->all();
    }

    private function displayValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }
        if (is_array($value)) {
            return collect($value)->flatten()->filter(fn ($item) => is_scalar($item))->implode(', ');
        }

        return is_scalar($value) ? $this->cleanText(trim((string) $value)) : '';
    }

    private function displayAnswerValue(mixed $value, string $key = ''): string
    {
        if (is_string($value) && str_starts_with(trim($value), '[')) {
            $value = json_decode($value, true) ?: $value;
        }
        if (is_array($value)) {
            return collect($value)->map(function ($item): string {
                if (is_array($item)) {
                    $label = $item['title'] ?? $item['label'] ?? $item['name'] ?? null;
                    $cost = (float) ($item['cost'] ?? 0);

                    return $label ? $this->cleanText((string) $label).($cost > 0 ? ' (Rs. '.number_format($cost, 2).' per guest)' : '') : '';
                }

                return is_scalar($item) ? $this->cleanText((string) $item) : '';
            })->filter()->implode(', ');
        }

        if (! is_scalar($value)) {
            return '';
        }

        if ($key === 'wedding_budget' && is_numeric($value)) {
            $budget = (float) $value;

            return '₹'.number_format($budget, floor($budget) === $budget ? 0 : 2).' lakh';
        }
        if ($key === 'guest_capacity' && is_numeric($value)) {
            return number_format((float) $value, 0).' guests';
        }

        return $this->cleanText((string) $value);
    }

    private function cleanText(string $value): string
    {
        $hadUnderscore = str_contains($value, '_');
        $value = trim(preg_replace('/_+/', ' ', $value) ?? $value);

        return $hadUnderscore ? Str::headline($value) : $value;
    }

    private function defaultDisplayContent(array $summary, array $answers, UserEventPlan $plan): array
    {
        $title = $this->cleanText((string) ($summary['title'] ?? $plan->title));
        $serviceCount = count($summary['costing'] ?? []);
        $answerCount = count($answers);

        return [
            'brand_label' => 'Shaadi Sense AI',
            'sidebar_title' => $title,
            'sidebar_description' => $plan->guest_count.' guests · '.$serviceCount.' costed services · '.$answerCount.' saved requirements',
            'estimated_total_label' => 'Estimated plan total',
            'guests_label' => 'Guests',
            'services_label' => 'Costed services',
            'download_label' => 'Download plan PDF',
            'new_plan_label' => 'Generate new plan',
            'dashboard_label' => 'User dashboard',
            'hero_badge' => $plan->guest_count.'-guest '.Str::headline($plan->category).' plan',
            'selection_eyebrow' => $answerCount.' saved requirements',
            'selection_title' => 'Selections used for '.$title,
            'costing_eyebrow' => $serviceCount.' costed service categories',
            'costing_title' => 'Detailed costing for '.$title,
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
}
