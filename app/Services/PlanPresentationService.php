<?php

namespace App\Services;

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
                'name' => (string) ($attribute['name'] ?? 'Service item'),
                'value' => (string) ($attribute['value'] ?? ''),
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

        return [
            'title' => (string) ($summary['title'] ?? $plan->title),
            'overview' => (string) ($summary['overview'] ?? 'Your personalized wedding plan.'),
            'total_cost' => (float) $plan->total_cost,
            'costing' => $costing,
            'recommendations' => $recommendations,
            'notes' => $summary['notes'] ?? [],
            'answers' => $plan->answers ?? [],
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

        return is_scalar($value) ? trim((string) $value) : '';
    }
}
