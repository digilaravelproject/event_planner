@php
    $type = $attribute['type'] ?? 'text';
    $isUpload = in_array($type, ['image', 'file', 'video'], true);
@endphp
<article class="attribute-row rounded-2xl border border-slate-200 bg-slate-50/60 p-4" draggable="true" data-index="{{ $index }}">
    <input type="hidden" data-attribute-id name="attributes[{{ $index }}][id]" value="{{ $attribute['id'] ?? '' }}">
    <div class="mb-4 flex items-center justify-between">
        <div class="flex cursor-grab items-center gap-2 text-xs font-extrabold uppercase tracking-wide text-slate-500"><span class="text-lg text-slate-400">⠿</span><span class="position-label">Attribute {{ is_numeric($index) ? $index + 1 : '' }}</span></div>
        <div class="flex gap-2"><button type="button" class="duplicate-attribute rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600">Duplicate</button><button type="button" class="remove-attribute rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-bold text-rose-600">Remove</button></div>
    </div>
    <div class="grid gap-4 md:grid-cols-12">
        <label class="block md:col-span-4"><span class="mb-1.5 block text-xs font-bold text-slate-700">Attribute name *</span><input required list="attribute-suggestions" name="attributes[{{ $index }}][label]" value="{{ $attribute['label'] ?? '' }}" class="attribute-name w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm"></label>
        <label class="block md:col-span-3"><span class="mb-1.5 block text-xs font-bold text-slate-700">Value type *</span><select name="attributes[{{ $index }}][type]" class="attribute-type w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm">@foreach($attributeTypes as $option)<option value="{{ $option }}" @selected($type === $option)>{{ str($option)->replace('_', ' ')->title() }}</option>@endforeach</select></label>
        <label class="attribute-value {{ $isUpload ? 'hidden' : '' }} block md:col-span-5"><span class="mb-1.5 block text-xs font-bold text-slate-700">Attribute value</span><textarea name="attributes[{{ $index }}][value]" rows="1" placeholder="{{ $attribute['placeholder'] ?? 'Enter a value' }}" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm">{{ $attribute['value'] ?? '' }}</textarea></label>
        <label class="attribute-upload {{ $isUpload ? '' : 'hidden' }} block md:col-span-5"><span class="mb-1.5 block text-xs font-bold text-slate-700">Upload</span><input type="file" name="attribute_uploads[{{ $index }}]" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm"><span class="mt-1 block truncate text-[11px] text-slate-400">{{ $isUpload && ($attribute['value'] ?? null) ? 'Current: '.$attribute['value'] : '' }}</span></label>
    </div>
    <details class="mt-4 rounded-xl border border-slate-200 bg-white p-3">
        <summary class="cursor-pointer text-xs font-extrabold text-slate-700">Optional validation & guidance</summary>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <label class="flex items-center gap-2 text-xs font-bold text-slate-700"><input type="hidden" name="attributes[{{ $index }}][required]" value="0"><input type="checkbox" name="attributes[{{ $index }}][required]" value="1" @checked(filter_var($attribute['required'] ?? false, FILTER_VALIDATE_BOOL))> Required</label>
            @foreach(['min_length' => 'Minimum length', 'max_length' => 'Maximum length', 'min_value' => 'Minimum value', 'max_value' => 'Maximum value'] as $field => $label)
                <label><span class="mb-1 block text-[11px] font-bold text-slate-600">{{ $label }}</span><input type="number" name="attributes[{{ $index }}][{{ $field }}]" value="{{ $attribute[$field] ?? '' }}" class="w-full rounded-lg border border-slate-200 px-2.5 py-2 text-xs"></label>
            @endforeach
            <label class="sm:col-span-2"><span class="mb-1 block text-[11px] font-bold text-slate-600">Allowed values (comma-separated)</span><input name="attributes[{{ $index }}][allowed_values]" value="{{ $attribute['allowed_values'] ?? '' }}" class="w-full rounded-lg border border-slate-200 px-2.5 py-2 text-xs"></label>
            @foreach(['placeholder' => 'Placeholder', 'help_text' => 'Help text', 'default_value' => 'Default value'] as $field => $label)
                <label><span class="mb-1 block text-[11px] font-bold text-slate-600">{{ $label }}</span><input name="attributes[{{ $index }}][{{ $field }}]" value="{{ $attribute[$field] ?? '' }}" class="w-full rounded-lg border border-slate-200 px-2.5 py-2 text-xs"></label>
            @endforeach
        </div>
    </details>
</article>
