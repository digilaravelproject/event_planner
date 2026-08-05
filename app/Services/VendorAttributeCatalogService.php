<?php

namespace App\Services;

use App\Models\EventRequirementQuestion;
use App\Modules\DynamicVendors\Models\DynamicVendor;
use Illuminate\Validation\ValidationException;

class VendorAttributeCatalogService
{
    private const UNSUPPORTED_TYPES = ['image', 'file', 'video', 'gps', 'rich_text', 'json'];

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
                ];

                foreach ($this->attributeValues($attribute['value'] ?? null) as $value) {
                    $canonical = $this->canonicalValue($value);
                    if ($canonical === null || mb_strlen($canonical) > 255) {
                        continue;
                    }

                    $catalog[$key]['values'][$canonical] ??= [
                        'value' => $canonical,
                        'label' => $this->displayValue($value),
                        'vendors_count' => 0,
                    ];
                    $catalog[$key]['values'][$canonical]['vendors_count']++;
                }
            }
        });

        $this->mergeSavedSelection($catalog, $question);

        foreach ($catalog as &$attribute) {
            $attribute['values'] = array_values($attribute['values']);
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
        $data['options'] = array_map(fn (string $value): string => $values[$value]['label'], $selected);

        return $data;
    }

    private function attributeValues(mixed $value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        return is_array($value) && array_is_list($value) ? $value : [$value];
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
        ];

        foreach ($question->vendor_attribute_values ?? [] as $index => $value) {
            $canonical = (string) $value;
            $catalog[$key]['values'][$canonical] ??= [
                'value' => $canonical,
                'label' => (string) ($question->options[$index] ?? $canonical),
                'vendors_count' => 0,
            ];
        }
    }
}
