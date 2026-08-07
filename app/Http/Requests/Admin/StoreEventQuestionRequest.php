<?php

namespace App\Http\Requests\Admin;

use App\Models\EventRequirementQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEventQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    protected function prepareForValidation(): void
    {
        $mappedValues = array_values(array_filter(array_map('strval', (array) $this->input('vendor_attribute_values', [])), fn (string $value): bool => $value !== ''));
        $mappedImages = array_values(array_filter(array_map('strval', (array) $this->input('vendor_attribute_images', [])), fn (string $value): bool => $value !== ''));
        $metadataValues = (array) $this->input('option_metadata_values', []);
        $metadataCategories = (array) $this->input('option_metadata_categories', []);
        $metadataCosts = (array) $this->input('option_metadata_costs', []);
        $optionMetadata = [];
        foreach ($metadataValues as $index => $value) {
            $value = (string) $value;
            if (! in_array($value, $mappedValues, true)) {
                continue;
            }
            $optionMetadata[$value] = [
                'category' => trim((string) ($metadataCategories[$index] ?? '')) ?: 'Menu Items',
                'cost' => max(0, round((float) ($metadataCosts[$index] ?? 0), 2)),
            ];
        }
        $this->merge([
            'is_required' => $this->boolean('is_required'),
            'status' => $this->boolean('status'),
            'options' => $mappedValues !== []
                ? $mappedValues
                : array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', (string) $this->input('options_text'))))),
            'vendor_attribute_key' => $this->filled('vendor_attribute_key') ? trim((string) $this->input('vendor_attribute_key')) : null,
            'vendor_attribute_values' => $mappedValues,
            'vendor_attribute_images' => $mappedImages,
            'option_metadata' => $optionMetadata,
        ]);
    }

    public function rules(): array
    {
        $questionId = $this->route('event_question')?->id;

        return [
            'question' => ['required', 'string', 'max:255'],
            'question_code' => ['required', 'string', 'max:100', Rule::unique(EventRequirementQuestion::class, 'question_code')->ignore($questionId)],
            'question_type' => ['required', 'string', 'max:50'],
            'placeholder' => ['nullable', 'string', 'max:255'],
            'display_order' => ['required', 'integer', 'min:0', 'max:9999'],
            'status' => ['boolean'],
        ];
    }
}
