<?php

namespace App\Services;

use App\Models\EventRequirementQuestion;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Validation\ValidationException;

class VendorAttributeCatalogService
{
    private const UNSUPPORTED_TYPES = ['image', 'file', 'video', 'gps', 'rich_text', 'json', 'phone', 'email', 'url'];

    public function catalog(?EventRequirementQuestion $question = null): array
    {
        $catalog = [];

        DynamicVendor::query()->select('vendor_json')->each(function (DynamicVendor $vendor) use (&$catalog): void {
            foreach (data_get($vendor->vendor_json, 'attributes', []) as $attribute) {
                $key = trim((string) ($attribute['key'] ?? ''));
                $label = trim((string) ($attribute['label'] ?? $key));
                $type = (string) ($attribute['type'] ?? 'text');

                if ($key === '' || $label === '' || in_array($type, self::UNSUPPORTED_TYPES, true)) {
                    continue;
                }

                $catalog[$key] ??= [
                    'key' => $key,
                    'label' => $label,
                    'type' => $type,
                    'values' => [],
                    'images' => [],
                ];

                foreach ($this->attributeImages($attribute) as $image) {
                    $catalog[$key]['images'][$image] = $image;
                }

                foreach ($this->attributeValues($attribute['value'] ?? null) as $value) {
                    $canonical = $this->canonicalValue($value);
                    if ($canonical === null || mb_strlen($canonical) > 255) {
                        continue;
                    }

                    $catalog[$key]['values'][$canonical] ??= [
                        'value' => $canonical,
                        'label' => $this->displayValue($value),
                        'vendors_count' => 0,
                        'vendor_names' => [],
                    ];
                    $catalog[$key]['values'][$canonical]['vendors_count']++;
                    $vendorName = $vendor->name;
                    if (! in_array($vendorName, $catalog[$key]['values'][$canonical]['vendor_names'], true)) {
                        $catalog[$key]['values'][$canonical]['vendor_names'][] = $vendorName;
                    }
                }
            }
        });

        $this->mergeSavedSelection($catalog, $question);

        foreach ($catalog as &$attribute) {
            $attribute['values'] = array_values($attribute['values']);
            $attribute['images'] = array_values($attribute['images']);
            usort($attribute['values'], fn (array $left, array $right): int => strnatcasecmp($left['label'], $right['label']));
        }
        unset($attribute);

        uasort($catalog, fn (array $left, array $right): int => strnatcasecmp($left['label'], $right['label']));

        return $catalog;
    }

    public function applyMapping(array $data, ?EventRequirementQuestion $question = null): array
    {
        $key = trim((string) ($data['vendor_attribute_key'] ?? ''));
        if ($key === '') {
            $data['vendor_attribute_key'] = null;
            $data['vendor_attribute_label'] = null;
            $data['vendor_attribute_values'] = null;
            $data['vendor_attribute_images'] = null;
            $data['option_metadata'] = null;

            return $data;
        }

        $catalog = $this->catalog($question);
        if (! isset($catalog[$key])) {
            throw ValidationException::withMessages([
                'vendor_attribute_key' => 'Select an attribute that exists in the dynamic vendor data.',
            ]);
        }

        $selected = array_values(array_unique(array_map('strval', $data['vendor_attribute_values'] ?? [])));
        if ($selected === []) {
            throw ValidationException::withMessages([
                'vendor_attribute_values' => 'Select at least one vendor attribute value.',
            ]);
        }

        $values = collect($catalog[$key]['values'])->keyBy('value');
        $unknown = array_values(array_diff($selected, $values->keys()->all()));
        if ($unknown !== []) {
            throw ValidationException::withMessages([
                'vendor_attribute_values' => 'One or more selected values are no longer available for this attribute.',
            ]);
        }

        $data['vendor_attribute_key'] = $key;
        $data['vendor_attribute_label'] = $catalog[$key]['label'];
        $data['vendor_attribute_values'] = $selected;
        $selectedImages = array_values(array_unique(array_map('strval', $data['vendor_attribute_images'] ?? [])));
        $unknownImages = array_values(array_diff($selectedImages, $catalog[$key]['images']));
        if ($unknownImages !== []) {
            throw ValidationException::withMessages([
                'vendor_attribute_images' => 'One or more selected images are no longer available for this attribute.',
            ]);
        }
        $data['vendor_attribute_images'] = $selectedImages;
        if (empty($data['options'])) {
            $data['options'] = array_map(fn (string $value): string => $values[$value]['label'], $selected);
        }
        $metadata = (array) ($data['option_metadata'] ?? []);
        $data['option_metadata'] = collect($selected)->mapWithKeys(function (string $value) use ($metadata, $values): array {
            $details = (array) ($metadata[$value] ?? []);

            return [$value => [
                'label' => (string) ($values[$value]['label'] ?? $value),
                'category' => trim((string) ($details['category'] ?? '')) ?: 'Menu Items',
                'cost' => max(0, round((float) ($details['cost'] ?? 0), 2)),
            ]];
        })->all();

        return $data;
    }

    private function attributeValues(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        return is_array($value) && array_is_list($value) ? $value : [$value];
    }

    private function attributeImages(array $attribute): array
    {
        $images = $attribute['images'] ?? [];
        if (($attribute['type'] ?? null) === 'image') {
            $value = $attribute['value'] ?? [];
            $images = array_merge((array) $images, is_array($value) ? $value : [$value]);
        }

        return array_values(array_filter($images, fn ($image): bool => is_string($image) && trim($image) !== ''));
    }

    private function canonicalValue(mixed $value): ?string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }
        if (is_int($value) || is_float($value)) {
            return rtrim(rtrim(sprintf('%.12F', $value), '0'), '.');
        }
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    private function displayValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        return (string) $value;
    }

    private function mergeSavedSelection(array &$catalog, ?EventRequirementQuestion $question): void
    {
        if ($question === null || ! $question->vendor_attribute_key) {
            return;
        }

        $key = $question->vendor_attribute_key;
        $catalog[$key] ??= [
            'key' => $key,
            'label' => $question->vendor_attribute_label ?: $key,
            'type' => 'text',
            'values' => [],
            'images' => [],
        ];

        foreach ($question->vendor_attribute_images ?? [] as $image) {
            $catalog[$key]['images'][$image] = $image;
        }

        foreach ($question->vendor_attribute_values ?? [] as $index => $value) {
            $canonical = (string) $value;
            $catalog[$key]['values'][$canonical] ??= [
                'value' => $canonical,
                'label' => (string) ($question->options[$index] ?? $canonical),
                'vendors_count' => 0,
                'vendor_names' => [],
            ];
        }
    }
}
