<?php

namespace App\Services;

use App\Models\EventRequirementQuestion;
use App\Models\UserEventPlan;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Support\Str;

class VendorAvailabilityService
{
    private ?array $locationMapping = null;

    public function assess(DynamicVendor $vendor, array $answers, int $guests): array
    {
        $attributes = collect(data_get($vendor->vendor_json, 'attributes', []))->filter(fn ($a) => is_array($a))->mapWithKeys(fn ($a) => [
            $a['key'] ?? Str::snake($a['label'] ?? '') => $a['value'] ?? null,
        ])->all();
        $schedule = (array) data_get($vendor->vendor_json, 'availability', []);
        $date = (string) ($answers['event_date'] ?? '');
        $time = (string) ($answers['event_time'] ?? '');
        $reasons = [];
        $unknown = [];
        $flag = $schedule['is_available'] ?? $attributes['currently_available'] ?? null;
        if (strtolower($vendor->status) !== 'active') {
            $reasons[] = 'This vendor is no longer active.';
        }
        if ($flag !== null && filter_var($flag, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) === false) {
            $reasons[] = 'The vendor has marked their services unavailable.';
        }
        $blocked = $this->values($schedule['unavailable_dates'] ?? $attributes['unavailable_dates'] ?? $attributes['booked_dates'] ?? []);
        $open = $this->values($schedule['available_dates'] ?? $attributes['available_dates'] ?? []);
        if ($date !== '' && in_array($date, $blocked, true)) {
            $reasons[] = 'Not available on '.$date.'.';
        }
        if ($date !== '' && $open !== [] && ! in_array($date, $open, true)) {
            $reasons[] = 'The selected date '.$date.' is not in the vendor’s available dates.';
        }
        if ($date === '') {
            $unknown[] = 'Choose an exact event date to check availability.';
        } elseif ($open === []) {
            $unknown[] = 'Availability for '.$date.' has not been confirmed by the vendor.';
        }

        $locations = $this->values($answers['service_area'] ?? []);
        if ($this->locationMapping === null) {
            $question = EventRequirementQuestion::enabled()->where('question_code', 'service_area')->first();
            $mapped = $question?->option_vendor_values ?: $question?->vendor_attribute_values ?: [];
            $this->locationMapping = collect($question?->options ?? [])->mapWithKeys(fn ($label, $index) => [(string) $label => (string) ($mapped[$index] ?? $label)])->all();
        }
        $locations = array_map(fn ($location) => $this->locationMapping[$location] ?? $location, $locations);
        $areas = $this->values(($schedule['service_areas'] ?? []) ?: ($attributes['supported_locations'] ?? $attributes['service_area'] ?? $attributes['city'] ?? []));
        if ($locations !== [] && $areas !== [] && ! array_intersect(array_map('mb_strtolower', $locations), array_map('mb_strtolower', $areas))) {
            $reasons[] = 'The vendor does not list service in '.implode(', ', $locations).'. Service areas: '.implode(', ', $areas).'.';
        } elseif ($locations === [] || $areas === []) {
            $unknown[] = 'Service-area coverage needs confirmation.';
        }
        $capacity = $attributes['max_guest_capacity'] ?? $attributes['guest_capacity'] ?? null;
        if (is_numeric($capacity) && (int) $capacity < $guests) {
            $reasons[] = 'Capacity is '.$capacity.' guests; your event has '.$guests.'.';
        }

        $start = substr((string) ($schedule['start_time'] ?? $attributes['service_start_time'] ?? ''), 0, 5);
        $end = substr((string) ($schedule['end_time'] ?? $attributes['service_end_time'] ?? ''), 0, 5);
        if ($time !== '' && $start && $end) {
            $within = $start <= $end ? ($time >= $start && $time <= $end) : ($time >= $start || $time <= $end);
            if (! $within) {
                $reasons[] = 'The selected time '.$time.' is outside service hours '.$start.'–'.$end.'.';
            }
        } else {
            $unknown[] = 'Event time and service hours need confirmation.';
        }
        if ($reasons !== [] && ! empty($schedule['reason'])) {
            $reasons[] = Str::limit((string) $schedule['reason'], 500);
        }

        return [
            'status' => $reasons !== [] ? 'unavailable' : ($unknown !== [] ? 'unconfirmed' : 'available'),
            'eligible' => $reasons === [],
            'messages' => $reasons !== [] ? $reasons : ($unknown ?: ['Available for the selected date and area according to the saved vendor schedule. Confirm before booking.']),
        ];
    }

    /** Re-check live database records without rewriting historical plan prices. */
    public function report(UserEventPlan $plan): array
    {
        $vendors = DynamicVendor::query()->get()->keyBy('id');
        $answers = (array) $plan->answers;
        $checks = $vendors->map(fn ($vendor) => $this->assess($vendor, $answers, $plan->guest_count));
        $snapshot = collect($plan->vendor_snapshot ?? [])->keyBy('id');
        $slots = [];
        $costing = app(VendorCostingService::class);
        foreach ($plan->summary['costing'] ?? [] as $index => $item) {
            $ids = array_values(array_unique(array_map('intval', $item['vendor_ids'] ?? [])));
            if ($ids === []) {
                $conflicts = $vendors->filter(fn ($vendor) => $costing->matches($vendor->category, $item['category']) && ! $checks[$vendor->id]['eligible'])
                    ->take(5)->map(fn ($vendor) => $vendor->name.': '.implode(' ', $checks[$vendor->id]['messages']))->values()->all();
                $slots['category_'.$index] = ['vendor_id' => null, 'name' => $item['category'], 'category' => $item['category'],
                    'status' => 'unavailable', 'messages' => array_merge(['No provider is assigned to this service. Choose a suitable vendor below.'], $conflicts)];
            }
            foreach ($ids as $id) {
                $vendor = $vendors->get($id);
                $saved = $snapshot->get($id, []);
                $slots['vendor_'.$id] = ['vendor_id' => $id, 'name' => $vendor?->name ?? $saved['name'] ?? 'Previous vendor',
                    'category' => $vendor?->category ?? $saved['category'] ?? $item['category']]
                    + ($checks->get($id) ?? ['status' => 'unavailable', 'messages' => ['This vendor record is no longer available.']]);
            }
        }
        // Retain explicit selections rejected during generation, so failures are not silent.
        foreach (array_unique(array_merge($answers['selected_caterers'] ?? [], $answers['preferred_vendor_ids'] ?? [])) as $id) {
            if (isset($slots['vendor_'.$id])) {
                continue;
            }
            $vendor = $vendors->get($id);
            $check = $checks->get($id);
            if (! $vendor || ! ($check['eligible'] ?? false)) {
                $slots['vendor_'.$id] = ['vendor_id' => (int) $id, 'name' => $vendor?->name ?? 'Previously selected vendor',
                    'category' => $vendor?->category ?? 'Catering'] + ($check ?? ['status' => 'unavailable', 'messages' => ['This vendor is no longer available.']]);
            }
        }
        foreach ($slots as &$slot) {
            $currentVendor = $vendors->get($slot['vendor_id']);
            if ($currentVendor && ! $this->supportsMenu($currentVendor, $answers, $slot['vendor_id'])) {
                $slot['status'] = 'unavailable';
                $slot['messages'][] = 'The selected menu, package or extras are no longer offered by this vendor. Choose a replacement or revise your food selection.';
            }
            $slot['alternatives'] = $vendors->filter(function ($vendor) use ($slot, $checks, $costing, $answers) {
                $sameService = $slot['vendor_id'] ? $costing->serviceKey($vendor->category) === $costing->serviceKey($slot['category']) : $costing->matches($vendor->category, $slot['category']);

                return $vendor->id !== $slot['vendor_id'] && $checks[$vendor->id]['eligible'] && $sameService
                    && $this->supportsMenu($vendor, $answers, $slot['vendor_id']);
            })->map(fn ($vendor) => ['id' => $vendor->id, 'name' => $vendor->name, 'status' => $checks[$vendor->id]['status'],
                'messages' => $checks[$vendor->id]['messages']])->sortBy(fn ($vendor) => $vendor['status'] === 'available' ? 0 : 1)->values()->all();
        }
        unset($slot);

        return $slots;
    }

    public function supportsMenu(DynamicVendor $vendor, array $answers, ?int $oldId): bool
    {
        if (app(VendorCostingService::class)->serviceKey($vendor->category) !== 'catering') {
            return true;
        }
        $items = collect($answers['food_menu_items'] ?? [])->filter(fn ($item) => $oldId === null || (int) ($item['vendor_id'] ?? 0) === $oldId);
        $menu = collect(data_get($vendor->vendor_json, 'attributes', []))->first(fn ($a) => strtolower($a['key'] ?? Str::snake($a['label'] ?? '')) === 'menu_card_items');
        $available = array_map('mb_strtolower', $this->values($menu['value'] ?? []));
        if ($items->contains(fn ($item) => ! in_array(mb_strtolower($item['title']), $available, true))) {
            return false;
        }
        $packageId = data_get($answers, 'selected_food_package.id');

        $extras = collect(data_get($vendor->vendor_json, 'food_extras', []))->pluck('id');

        return (! $packageId || collect(data_get($vendor->vendor_json, 'food_packages', []))->contains('id', $packageId))
            && collect($answers['selected_food_extras'] ?? [])->diff($extras)->isEmpty();
    }

    private function values(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : preg_split('/[,\r\n]+/', $value);
        }

        return array_values(array_filter(array_map(fn ($value) => is_scalar($value) ? trim((string) $value) : '', (array) $value), fn ($value) => $value !== ''));
    }
}
