<?php

namespace App\Services;

use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Support\Str;

class PlanSuggestionService
{
    public function __construct(private VendorCostingService $costing) {}

    /** Swap one comparable provider at a time; never invent discounts or remove services. */
    public function alternatives(UserEventPlan $plan, array $vendors): array
    {
        $current = collect($vendors)->keyBy('id');
        $saved = collect($plan->vendor_snapshot ?? [])->keyBy('id');
        $base = $plan->summary;
        $lines = collect($base['costing'] ?? [])->flatMap(fn ($item) => $item['attributes'] ?? []);
        $used = $lines->pluck('vendor_id')->filter()->unique();
        $results = [];
        // Old aggregate-only estimates cannot support an honest item-by-item comparison.
        if (($base['pricing_version'] ?? 0) < 2 || abs($lines->sum('cost') - (float) $plan->total_cost) > .01) {
            return [];
        }

        foreach ($used as $oldId) {
            $old = $saved->get($oldId);
            $oldLines = $lines->where('vendor_id', $oldId)->values();
            if (! $old || $oldLines->contains(fn ($line) => ($line['pricing_status'] ?? '') !== 'priced')) {
                continue;
            }
            if ($used->reject(fn ($id) => $id == $oldId)->diff($current->keys())->isNotEmpty()) {
                continue;
            }
            foreach ($current as $vendor) {
                if ($used->contains($vendor['id']) || $this->costing->serviceKey($vendor['category']) !== $this->costing->serviceKey($old['category'])) {
                    continue;
                }
                $replacement = $this->replacementLines($oldLines->all(), $vendor, $plan);
                if ($replacement === null) {
                    continue;
                }
                $delta = round(collect($replacement)->sum('cost') - $oldLines->sum('cost'), 2);
                if (abs($delta) < .01) {
                    continue;
                }
                $summary = $base;
                unset($summary['target_budget'], $summary['comparison']);
                $offset = 0;
                foreach ($summary['costing'] as &$item) {
                    foreach ($item['attributes'] as &$line) {
                        if (($line['vendor_id'] ?? null) == $oldId) {
                            $line = $replacement[$offset++];
                        }
                    }
                    unset($line);
                    $item['amount'] = round(collect($item['attributes'])->sum('cost'), 2);
                    $item['vendor_ids'] = collect($item['attributes'])->pluck('vendor_id')->filter()->unique()->values()->all();
                    if (in_array($vendor['id'], $item['vendor_ids'], true)) {
                        $item['summary'] = 'Comparable services from '.$vendor['name'].'. Confirm scope before booking.';
                    }
                }
                unset($item);
                $total = round(collect($summary['costing'])->sum('amount'), 2);
                foreach ($summary['costing'] as &$item) {
                    $item['percentage'] = $total > 0 ? round($item['amount'] / $total * 100, 1) : 0;
                }
                unset($item);
                $title = Str::limit($vendor['name'].' · '.$old['category'].' alternative', 240);
                $summary['title'] = $title;
                $summary['total_cost'] = $total;
                $summary['suggestion_version'] = 2;
                $summary['overview'] = 'Replace '.$old['name'].' with '.$vendor['name'].'. Same priced service items and quantities; other saved costs stay unchanged. Confirm equivalent scope with the vendor.';
                $summary['comparison'] = [
                    'tier' => $delta < 0 ? 'Lower-cost vendor' : 'Higher-cost vendor',
                    'change_label' => 'Rs. '.number_format(abs($delta), 2).' '.($delta < 0 ? 'less' : 'more').' than your original plan',
                    'requirements_label' => 'Guest count and service items retained',
                    'costing_label' => 'Actual replacement rates',
                    'replaced_vendor_id' => (int) $oldId, 'replacement_vendor_id' => $vendor['id'],
                    'difference' => $delta,
                    'image' => $delta < 0 ? 'images/planner/value-wedding-plan.webp' : 'images/planner/premium-wedding-plan.webp',
                ];
                $answers = $plan->answers;
                $answers['preferred_vendor_ids'] = $used->map(fn ($id) => $id == $oldId ? $vendor['id'] : (int) $id)->values()->all();
                if ($this->costing->serviceKey($vendor['category']) === 'catering') {
                    $answers['selected_caterers'] = collect($answers['selected_caterers'] ?? [])->map(fn ($id) => $id == $oldId ? $vendor['id'] : (int) $id)->unique()->values()->all();
                    foreach ($answers['food_menu_items'] ?? [] as $index => $selection) {
                        if (($selection['vendor_id'] ?? null) != $oldId) {
                            continue;
                        }
                        $rate = collect($replacement)->first(fn ($line) => mb_strtolower($line['name']) === mb_strtolower($selection['title']));
                        $answers['food_menu_items'][$index] = array_merge($selection, ['vendor_id' => $vendor['id'], 'vendor_name' => $vendor['name'], 'cost' => $rate['unit_price'], 'source' => 'vendor_attribute']);
                    }
                    if (! empty($answers['selected_food_package'])) {
                        $answers['selected_food_package'] = collect($vendor['food_packages'] ?? [])->firstWhere('id', $answers['selected_food_package']['id']);
                    }
                }
                $snapshot = $saved->replace([$vendor['id'] => $vendor])->values()->all();
                $finalLines = collect($summary['costing'])->flatMap(fn ($item) => $item['attributes']);
                $summary['recommendations'] = collect($snapshot)->whereIn('id', $finalLines->pluck('vendor_id'))->map(fn ($provider) => [
                    'vendor_id' => $provider['id'], 'name' => $provider['name'], 'category' => $provider['category'],
                    'reason' => 'Provider of the priced services in this alternative.', 'estimated_cost' => $finalLines->where('vendor_id', $provider['id'])->sum('cost'),
                ])->values()->all();
                $results[] = ['summary' => $summary, 'answers' => $answers, 'vendor_snapshot' => $snapshot];
            }
        }
        // Do not fill six cards with duplicate totals or imply a premium service from price alone.
        $distinct = collect($results)->sortBy('summary.total_cost')->unique(fn ($result) => number_format($result['summary']['total_cost'], 2, '.', ''));

        return $distinct->filter(fn ($result) => $result['summary']['total_cost'] < (float) $plan->total_cost)->take(3)
            ->concat($distinct->filter(fn ($result) => $result['summary']['total_cost'] > (float) $plan->total_cost)->take(3))->values()->all();
    }

    private function replacementLines(array $oldLines, array $vendor, UserEventPlan $plan): ?array
    {
        if (collect($oldLines)->contains('source', 'vendor_package')) {
            $answers = array_merge($plan->answers, ['selected_caterers' => [$vendor['id']]]);
            $rates = $this->costing->foodPackageLines($answers, [$vendor], $plan->guest_count);
        } else {
            $rates = $this->costing->catalog($vendor, $plan->guest_count);
        }
        $result = [];
        $taken = [];
        foreach ($oldLines as $old) {
            $match = collect($rates)->search(function ($rate, $index) use ($old, $taken) {
                return ! in_array($index, $taken, true) && ($rate['pricing_status'] ?? '') === 'priced'
                    && ($rate['unit'] ?? '') === ($old['unit'] ?? '')
                    && (float) ($rate['quantity'] ?? 0) === (float) ($old['quantity'] ?? 0)
                    && ((! empty($old['attribute_key']) && ($rate['attribute_key'] ?? '') === $old['attribute_key'])
                        || mb_strtolower($rate['name']) === mb_strtolower($old['name']));
            });
            if ($match === false) {
                return null;
            }
            $taken[] = $match;
            $result[] = $rates[$match];
        }
        // Require menu support, not merely a coincidentally named rate.
        if ($this->costing->serviceKey($vendor['category']) === 'catering') {
            $record = DynamicVendor::find($vendor['id']);
            if (! $record || ! app(VendorCompatibilityService::class)->supportsMenu($record, $plan->answers, (int) $oldLines[0]['vendor_id'])) {
                return null;
            }
        }

        return $result;
    }
}
