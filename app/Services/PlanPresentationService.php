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
        $legacy = ($summary['pricing_version'] ?? 1) < 2;
        $costing = collect($summary['costing'] ?? [])->map(function (array $item) use ($vendors, $legacy): array {
            $amount = (float) ($item['amount'] ?? 0);
            $matchedVendors = collect($item['vendor_ids'] ?? [])->map(fn ($id) => $vendors->get((int) $id))->filter()->values();
            if ($matchedVendors->isEmpty() && $legacy) {
                $matchedVendors = $this->vendorsForCategory($vendors->values(), (string) ($item['category'] ?? ''))->take(3)->values();
            }

            $savedAttributes = $item['attributes'] ?? [];
            if ($savedAttributes === [] && $amount > 0) {
                $savedAttributes = [['name' => 'Saved service estimate', 'value' => 'This older plan has no itemized vendor rates. Regenerate the plan for a current breakdown.', 'cost' => $amount, 'source' => 'legacy_estimate']];
            }
            $attributes = collect($savedAttributes)->map(function ($attribute) use ($vendors): array {
                $savedValue = $this->cleanText((string) ($attribute['value'] ?? ''));

                return array_merge($attribute, [
                    'name' => $this->cleanText((string) ($attribute['name'] ?? 'Service item')),
                    'value' => strcasecmp($savedValue, 'Indicative allocation') === 0 ? '' : $savedValue,
                    'vendor_name' => (string) data_get($vendors->get((int) ($attribute['vendor_id'] ?? 0)), 'name', $attribute['vendor_name'] ?? ''),
                    'cost' => max(0, (float) ($attribute['cost'] ?? 0)),
                ]);
            })->filter(fn (array $attribute): bool => $attribute['name'] !== '')->values();

            $attributeTotal = (float) $attributes->sum('cost');

            return array_merge($item, [
                'category' => $this->cleanText((string) ($item['category'] ?? 'Service')),
                'summary' => $this->cleanText((string) ($item['summary'] ?? '')),
                'amount' => $amount,
                'attributes' => $attributes->all(),
                'cost_warning' => abs($attributeTotal - $amount) > 0.01 ? 'Saved line items differ from this category total. Regenerate this older plan to reconcile the costing.' : null,
                'vendor_groups' => $attributes->groupBy(fn ($attribute) => $attribute['vendor_id'] ?? 'unassigned')->map(fn ($lines) => [
                    'name' => $lines->first()['vendor_name'] ?: 'Service estimate',
                    'amount' => round((float) $lines->sum('cost'), 2),
                    'attributes' => $lines->values()->all(),
                ])->values()->all(),
                'vendors' => $matchedVendors->map(fn (array $vendor): array => $this->presentVendor($vendor))->all(),
            ]);
        })->values()->all();

        $planVendors = collect($costing)->pluck('vendors')->flatten(1)->unique('id')->values()->all();

        $recommendationSource = collect($summary['recommendations'] ?? []);
        if ($recommendationSource->isEmpty() && $legacy) {
            $recommendationSource = $vendors->values()->take(8)->map(fn (array $vendor): array => [
                'vendor_id' => $vendor['id'],
                'name' => $vendor['name'],
                'category' => $vendor['category'],
                'reason' => 'Active vendor retained in the saved plan snapshot for requirement comparison.',
                'estimated_cost' => 0,
            ]);
        }

        $recommendations = $recommendationSource->map(function (array $recommendation) use ($vendors, $costing, $legacy): array {
            $vendor = $vendors->get((int) ($recommendation['vendor_id'] ?? 0), []);
            $attributes = collect((array) ($vendor['attributes'] ?? []))->map(function ($value, $key): array {
                return [
                    'name' => Str::of((string) $key)->replace('_', ' ')->title()->toString(),
                    'value' => $this->displayValue($value),
                ];
            })->filter(fn (array $attribute): bool => $attribute['value'] !== '')->take(8)->values();

            $estimated = (float) ($recommendation['estimated_cost'] ?? 0);
            if ($estimated <= 0 && $legacy) {
                $category = strtolower((string) ($recommendation['category'] ?? ''));
                $matchingCost = collect($costing)->first(fn (array $item) => $category !== '' && str_contains(strtolower((string) ($item['category'] ?? '')), $category));
                $estimated = (float) data_get($matchingCost, 'amount', 0);
            }

            return array_merge($recommendation, [
                'name' => (string) ($vendor['name'] ?? $recommendation['name'] ?? 'Matched vendor'),
                'category' => (string) ($vendor['category'] ?? $recommendation['category'] ?? 'Vendor'),
                'estimated_cost' => $estimated,
                'attributes' => $attributes->all(),
            ]);
        })->values()->all();

        $answers = (array) ($plan->answers ?? []);
        $displayAnswers = $answers;
        unset($displayAnswers['preferred_vendor_ids']);
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
            'plan_vendors' => $planVendors,
        ];
    }

    private function vendorsForCategory($vendors, string $category)
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
            if ($selectedGroup) {
                return collect($selectedGroup)->contains(fn (string $word): bool => str_contains($vendorText, $word));
            }

            return collect(preg_split('/[^a-z0-9]+/', $category) ?: [])->filter(fn (string $word): bool => strlen($word) > 3)
                ->contains(fn (string $word): bool => str_contains($vendorText, $word));
        })->sortBy(function (array $vendor) use ($selectedGroup): int {
            if (! $selectedGroup) {
                return 0;
            }
            $vendorText = strtolower((string) ($vendor['category'] ?? '').' '.(string) ($vendor['name'] ?? ''));

            return (int) (collect($selectedGroup)->search(fn (string $word): bool => str_contains($vendorText, $word)) ?: 0);
        })->values();
    }

    private function presentVendor(array $vendor): array
    {
        $priceAttribute = collect((array) ($vendor['attributes'] ?? []))->first(function ($attribute, $key): bool {
            if (is_array($attribute)) {
                return strtolower((string) ($attribute['key'] ?? $attribute['label'] ?? '')) === 'price';
            }

            return strtolower((string) $key) === 'price';
        });

        return [
            'id' => (int) ($vendor['id'] ?? 0),
            'name' => (string) ($vendor['name'] ?? 'Vendor'),
            'category' => (string) ($vendor['category'] ?? 'Vendor'),
            'price' => max(0, (float) (is_array($priceAttribute) ? ($priceAttribute['value'] ?? 0) : $priceAttribute)),
        ];
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
