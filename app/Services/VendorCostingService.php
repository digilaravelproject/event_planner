<?php

namespace App\Services;

use Illuminate\Support\Str;

/** Prices are resolved here, never taken from generated AI text. */
class VendorCostingService
{
    public function catalog(array $vendor, int $guests): array
    {
        $definitions = $vendor['attribute_definitions'] ?? $vendor['attributes'] ?? [];
        $rows = [];
        foreach ($definitions as $key => $definition) {
            $attribute = is_array($definition) ? $definition : ['key' => $key, 'value' => $definition];
            $key = (string) ($attribute['key'] ?? Str::snake($attribute['label'] ?? (string) $key));
            $label = (string) ($attribute['label'] ?? Str::headline($key));
            $pricing = (array) ($attribute['pricing'] ?? []);
            $monetary = ($attribute['type'] ?? '') === 'currency'
                || preg_match('/(^|_)(price|cost|rate|fee|rent)(_|$)/i', $key);
            $rate = $pricing['rate'] ?? ($monetary ? ($attribute['value'] ?? null) : null);
            if (! is_numeric($rate) || (float) $rate < 0 || ! is_finite((float) $rate)) {
                continue;
            }
            // Deposits, budgets and discounts are not additive service costs.
            if (preg_match('/deposit|advance|discount|budget|tax|gst|(^|_)(min|max)(_|$)/i', $key)) {
                continue;
            }
            $unit = $pricing['unit'] ?? (preg_match('/per_(guest|person|plate)/i', $key) ? 'per_guest' : 'fixed');
            $quantity = $unit === 'per_guest' ? $guests : ($unit === 'fixed' ? 1 : ($pricing['quantity'] ?? null));
            $quantity = is_numeric($quantity) && (float) $quantity > 0 ? (float) $quantity : null;
            $rows[] = [
                'attribute_key' => $key, 'name' => $label,
                'vendor_id' => (int) $vendor['id'], 'vendor_name' => $vendor['name'],
                'unit_price' => round((float) $rate, 2), 'unit' => $unit, 'quantity' => $quantity,
                'cost' => $quantity === null ? 0 : round(round((float) $rate, 2) * $quantity, 2),
                'value' => $pricing['description'] ?? ($monetary ? '' : $this->value($attribute['value'] ?? '')),
                'source' => 'vendor_attribute', 'pricing_status' => $quantity === null ? 'quote_required' : 'priced',
            ];
        }

        // A generic package price is an alternative to itemized rates, not an extra charge.
        $details = array_values(array_filter($rows, fn ($row) => ! in_array($row['attribute_key'], ['price', 'cost', 'package_price', 'total_price', 'base_price'], true)));

        return $details !== [] ? $details : array_slice($rows, 0, 1);
    }

    public function ground(array $summary, array $vendors, int $guests): array
    {
        $available = collect($vendors)->keyBy('id');
        $used = [];
        $billed = [];
        $costing = [];
        foreach ($available->where('preferred', true) as $vendor) {
            if (! collect($summary['costing'] ?? [])->contains(fn ($item) => $this->matches($vendor['category'], $item['category'] ?? ''))) {
                $summary['costing'][] = ['category' => $vendor['category'], 'vendor_ids' => [$vendor['id']]];
            }
        }
        foreach ($summary['costing'] ?? [] as $item) {
            $category = (string) ($item['category'] ?? 'Service');
            $candidates = $available->filter(fn ($vendor) => $this->matches($vendor['category'], $category));
            $requested = collect($item['vendor_ids'] ?? [])->map(fn ($id) => (int) $id);
            $explicit = $available->only($requested->all());
            if ($explicit->isNotEmpty()) {
                $services = $explicit->map(fn ($vendor) => $this->serviceKey($vendor['category']));
                $candidates = $explicit->union($available->filter(fn ($vendor) => ! empty($vendor['preferred']) && $services->contains($this->serviceKey($vendor['category']))));
            }
            // Choose one provider per vendor category; never bill every alternative.
            $chosen = $candidates->sortByDesc(fn ($vendor) => (! empty($vendor['preferred']) ? 100000 : 0) + ($vendor['match_score'] ?? 0) * 10 + ($requested->contains($vendor['id']) ? 1 : 0))
                ->groupBy(fn ($vendor) => $this->serviceKey($vendor['category']))->map->first();
            $lines = [];
            foreach ($chosen as $vendor) {
                $rates = $this->catalog($vendor, $guests);
                $selectedKeys = collect($item['attributes'] ?? [])->where('vendor_id', $vendor['id'])->pluck('attribute_key')->filter()->all();
                if ($selectedKeys !== []) {
                    $rates = array_values(array_filter($rates, fn ($rate) => in_array($rate['attribute_key'], $selectedKeys, true)));
                }
                $rates = array_values(array_filter($rates, fn ($rate) => ! isset($billed[$vendor['id'].':'.$rate['attribute_key']])));
                if ($rates === [] && isset($used[$vendor['id']])) {
                    continue;
                }
                $used[$vendor['id']] = true;
                foreach ($rates as $rate) {
                    $billed[$vendor['id'].':'.$rate['attribute_key']] = true;
                }
                $lines = array_merge($lines, $rates ?: [[
                    'name' => $vendor['category'].' service', 'vendor_id' => $vendor['id'], 'vendor_name' => $vendor['name'],
                    'value' => 'No service rate saved by this vendor.', 'cost' => 0, 'pricing_status' => 'quote_required',
                ]]);
            }
            $amount = round((float) collect($lines)->sum('cost'), 2);
            $costing[] = [
                'category' => $category, 'amount' => $amount, 'percentage' => 0,
                'summary' => $lines === [] ? 'No matching vendor with confirmed pricing. Request a quote.' : 'Saved vendor rates. Confirm scope and taxes before booking.',
                'vendor_ids' => collect($lines)->pluck('vendor_id')->unique()->values()->all(), 'attributes' => $lines,
                'pricing_status' => $lines === [] || collect($lines)->contains('pricing_status', 'quote_required') ? 'quote_required' : 'priced',
            ];
        }
        $total = round((float) collect($costing)->sum('amount'), 2);
        $summary['costing'] = array_map(function ($item) use ($total) {
            $item['percentage'] = $total > 0 ? round($item['amount'] / $total * 100, 1) : 0;

            return $item;
        }, $costing);
        $summary['total_cost'] = $total;
        $summary['pricing_version'] = 2;
        $summary['recommendations'] = $available->only(array_keys($used))->map(fn ($vendor) => [
            'vendor_id' => $vendor['id'], 'name' => $vendor['name'], 'category' => $vendor['category'],
            'reason' => 'Selected from saved vendor data; confirm all requirements before booking.',
            'estimated_cost' => collect($costing)->flatMap(fn ($item) => $item['attributes'])->where('vendor_id', $vendor['id'])->sum('cost'),
        ])->values()->all();
        $summary['notes'] = array_values(array_unique(array_merge($summary['notes'] ?? [], [
            'Only priced services are included in the total. Items marked Quote required are excluded.',
            'Rates are a saved database snapshot, not live quotations. Confirm taxes and final pricing with the vendor.',
        ])));

        return $summary;
    }

    public function matches(string $vendorCategory, string $category): bool
    {
        $vendorCategory = strtolower($vendorCategory);
        $category = strtolower($category);
        foreach ([['venue', 'stay', 'hotel', 'banquet', 'resort'], ['cater', 'food', 'menu'], ['decor', 'flor', 'mandap'], ['photo', 'video', 'media', 'entertain', 'dj', 'sound'], ['plan', 'transport', 'travel', 'coordination']] as $group) {
            if (collect($group)->contains(fn ($word) => str_contains($category, $word))) {
                return collect($group)->contains(fn ($word) => str_contains($vendorCategory, $word));
            }
        }

        return $vendorCategory !== '' && (str_contains($category, $vendorCategory) || str_contains($vendorCategory, $category));
    }

    public function foodPackageLines(array $answers, array $vendors, int $guests): array
    {
        $selection = $answers['selected_food_package'] ?? [];
        if (is_string($selection)) {
            $selection = json_decode($selection, true) ?: [];
        }
        $selectedVendors = array_map('intval', (array) ($answers['selected_caterers'] ?? []));
        $vendor = collect($vendors)->first(fn ($vendor) => in_array($vendor['id'], $selectedVendors, true)
            && collect($vendor['food_packages'] ?? [])->contains('id', $selection['id'] ?? ''));
        $package = $vendor ? collect($vendor['food_packages'])->firstWhere('id', $selection['id']) : null;
        if (! $package) {
            return [['name' => 'Selected food package', 'value' => 'No matching saved package rate for the selected vendor. Request a quote.', 'cost' => 0, 'pricing_status' => 'quote_required']];
        }
        $services = [[
            'name' => $package['name'], 'min' => $package['min_price_per_plate'] ?? null,
            'max' => $package['max_price_per_plate'] ?? null, 'unit' => 'per_guest',
        ]];
        foreach ($vendor['food_extras'] ?? [] as $extra) {
            if (in_array($extra['id'] ?? '', $answers['selected_food_extras'] ?? [], true)
                && ! in_array($extra['name'], $package['items'] ?? [], true)) {
                $services[] = ['name' => $extra['name'], 'min' => $extra['min_price'] ?? null, 'max' => $extra['max_price'] ?? null,
                    'unit' => ($extra['unit'] ?? '') === 'per_plate' ? 'per_guest' : ($extra['unit'] ?? '')];
            }
        }

        return array_map(function ($service) use ($vendor, $guests) {
            $rate = is_numeric($service['min']) ? max(0, (float) $service['min']) : null;
            $quantity = match ($service['unit']) {
                'per_guest' => $guests, 'fixed' => 1, default => null
            };

            return [
                'name' => $service['name'], 'vendor_id' => $vendor['id'], 'vendor_name' => $vendor['name'],
                'unit_price' => $rate, 'unit' => $service['unit'], 'quantity' => $quantity,
                'cost' => $rate !== null && $quantity !== null ? round($rate * $quantity, 2) : 0,
                'source' => 'vendor_package', 'pricing_status' => $rate !== null && $quantity !== null ? 'priced' : 'quote_required',
                'value' => 'Saved package/extra starting rate'.(is_numeric($service['max']) ? ' (range Rs. '.number_format($rate ?? 0, 2).' - Rs. '.number_format($service['max'], 2).')' : '').'. Confirm the final quote.',
            ];
        }, $services);
    }

    public function serviceKey(string $category): string
    {
        foreach (['venue' => '/venue|stay|hotel|banquet|resort|hall/i', 'catering' => '/cater|food|menu/i', 'decor' => '/decor|flor|mandap/i', 'photography' => '/photo|video|media/i', 'entertainment' => '/entertain|dj|sound/i', 'transport' => '/transport|travel/i', 'planning' => '/plan|coordinat/i'] as $key => $pattern) {
            if (preg_match($pattern, $category)) {
                return $key;
            }
        }

        return strtolower($category);
    }

    private function value(mixed $value): string
    {
        return is_array($value) ? implode(', ', array_filter($value, 'is_scalar')) : (string) $value;
    }
}
