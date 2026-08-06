@php
    $type = $attribute['type'] ?? 'text';
    $isUpload = in_array($type, ['image', 'file', 'video'], true);
@endphp
<article class="attribute-row admin-card-interactive rounded-2xl border border-slate-200 bg-gradient-to-br from-white to-slate-50/80 p-4 shadow-sm" draggable="true" data-index="{{ $index }}">
    <input type="hidden" data-attribute-id name="attributes[{{ $index }}][id]" value="{{ $attribute['id'] ?? '' }}">
    <div class="mb-4 flex items-center justify-between">
        <div class="flex cursor-grab items-center gap-2 text-xs font-extrabold uppercase tracking-wide text-[#3950a2]"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-lg text-indigo-400">⠿</span><span class="position-label">Attribute {{ is_numeric($index) ? $index + 1 : '' }}</span></div>
        <div class="flex gap-2"><button type="button" class="duplicate-attribute rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600">Duplicate</button><button type="button" class="remove-attribute rounded-lg border border-rose-200 bg-white px-3 py-1.5 text-xs font-bold text-rose-600">Remove</button></div>
    </div>
    <div class="grid gap-4 md:grid-cols-12">
        <label class="block md:col-span-4"><span class="mb-1.5 block text-xs font-bold text-slate-700">Attribute name *</span><input required list="attribute-suggestions" name="attributes[{{ $index }}][label]" value="{{ $attribute['label'] ?? '' }}" class="attribute-name w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm"></label>
        <label class="block md:col-span-3"><span class="mb-1.5 block text-xs font-bold text-slate-700">Value type *</span><select name="attributes[{{ $index }}][type]" class="attribute-type w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm">@foreach($attributeTypes as $option)<option value="{{ $option }}" @selected($type === $option)>{{ str($option)->replace('_', ' ')->title() }}</option>@endforeach</select></label>
        <label class="attribute-value {{ $isUpload ? 'hidden' : '' }} block md:col-span-5"><span class="mb-1.5 block text-xs font-bold text-slate-700">Attribute value</span><textarea name="attributes[{{ $index }}][value]" rows="1" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm">{{ $attribute['value'] ?? '' }}</textarea></label>
        <label class="attribute-upload {{ $isUpload ? '' : 'hidden' }} block md:col-span-5"><span class="mb-1.5 block text-xs font-bold text-slate-700">Upload</span><input type="file" name="attribute_uploads[{{ $index }}]" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm"><span class="mt-1 block truncate text-[11px] text-slate-400">{{ $isUpload && ($attribute['value'] ?? null) ? 'Current: '.$attribute['value'] : '' }}</span></label>
    </div>
    <div class="mt-4 rounded-xl border border-indigo-100 bg-indigo-50/40 p-3">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div><p class="text-xs font-extrabold text-slate-700">Attribute images</p><p class="text-[11px] text-slate-500">Attach one or more images to this attribute.</p></div>
            <input type="file" name="attribute_images[{{ $index }}][]" accept="image/*" multiple class="text-xs text-slate-600">
        </div>
        @if(!empty($attribute['images']))
            <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                @foreach($attribute['images'] as $image)
                    <label class="rounded-lg border border-slate-200 bg-white p-2">
                        <img src="{{ asset('storage/'.$image) }}" alt="{{ $attribute['label'] ?? 'Attribute' }}" class="h-20 w-full rounded-md object-cover">
                        <span class="mt-1 flex items-center gap-1 text-[10px] font-bold text-slate-500"><input type="checkbox" name="existing_attribute_images[{{ $index }}][]" value="{{ $image }}" checked> Keep</span>
                    </label>
                @endforeach
            </div>
        @endif
    </div>
    <details class="mt-4 rounded-xl border border-slate-200 bg-white p-3">
        <summary class="cursor-pointer text-xs font-extrabold text-slate-700">Optional validation</summary>
        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <label class="flex items-center gap-2 text-xs font-bold text-slate-700"><input type="hidden" name="attributes[{{ $index }}][required]" value="0"><input type="checkbox" name="attributes[{{ $index }}][required]" value="1" @checked(filter_var($attribute['required'] ?? false, FILTER_VALIDATE_BOOL))> Required</label>
            @foreach(['min_length' => 'Minimum length', 'max_length' => 'Maximum length', 'min_value' => 'Minimum value', 'max_value' => 'Maximum value'] as $field => $label)
                <label><span class="mb-1 block text-[11px] font-bold text-slate-600">{{ $label }}</span><input type="number" name="attributes[{{ $index }}][{{ $field }}]" value="{{ $attribute[$field] ?? '' }}" class="w-full rounded-lg border border-slate-200 px-2.5 py-2 text-xs"></label>
            @endforeach
            <label class="sm:col-span-2"><span class="mb-1 block text-[11px] font-bold text-slate-600">Allowed values (comma-separated)</span><input name="attributes[{{ $index }}][allowed_values]" value="{{ $attribute['allowed_values'] ?? '' }}" class="w-full rounded-lg border border-slate-200 px-2.5 py-2 text-xs"></label>
            <label><span class="mb-1 block text-[11px] font-bold text-slate-600">Default value</span><input name="attributes[{{ $index }}][default_value]" value="{{ $attribute['default_value'] ?? '' }}" class="w-full rounded-lg border border-slate-200 px-2.5 py-2 text-xs"></label>
        </div>
    </details>
</article>
