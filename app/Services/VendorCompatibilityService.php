<?php

namespace App\Services;

use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Support\Str;

class VendorCompatibilityService
{
    public function supportsMenu(DynamicVendor $vendor, array $answers, ?int $oldId): bool
    {
        if (app(VendorCostingService::class)->serviceKey($vendor->category) !== 'catering') {
            return true;
        }

        $items = collect($answers['food_menu_items'] ?? [])->filter(fn ($item) => $oldId === null || (int) ($item['vendor_id'] ?? 0) === $oldId);
        $menu = collect(data_get($vendor->vendor_json, 'attributes', []))->first(fn ($attribute) => strtolower($attribute['key'] ?? Str::snake($attribute['label'] ?? '')) === 'menu_card_items');
        $availableItems = array_map('mb_strtolower', $this->values($menu['value'] ?? []));
        if ($items->contains(fn ($item) => ! in_array(mb_strtolower($item['title']), $availableItems, true))) {
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

        return array_values(array_filter(array_map(fn ($item) => is_scalar($item) ? trim((string) $item) : '', (array) $value), fn ($item) => $item !== ''));
    }
}
