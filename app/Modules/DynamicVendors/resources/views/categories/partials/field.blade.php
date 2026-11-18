@php
    $field = $field ?? [];
    $options = $field['options'] ?? '';
    if (is_array($options)) $options = implode(', ', $options);
@endphp
<div data-category-field class="category-field-row grid gap-4 rounded-2xl border border-orange-100 bg-orange-50/30 p-4 md:grid-cols-12">
    <label class="md:col-span-3"><span class="admin-form-label">Field label *</span><input required name="fields[{{ $index }}][label]" value="{{ $field['label'] ?? '' }}" class="admin-control w-full px-3 py-2.5" placeholder="Contact person"></label>
    <label class="md:col-span-2"><span class="admin-form-label">Input type *</span><select name="fields[{{ $index }}][type]" class="admin-control w-full px-3 py-2.5">@foreach($attributeTypes as $type)<option value="{{ $type }}" @selected(($field['type'] ?? 'text') === $type)>{{ ucwords(str_replace('_', ' ', $type)) }}</option>@endforeach</select></label>
    <label class="md:col-span-3"><span class="admin-form-label">Help text</span><input name="fields[{{ $index }}][help]" value="{{ $field['help'] ?? '' }}" class="admin-control w-full px-3 py-2.5" placeholder="What should be entered?"></label>
    <label class="md:col-span-3"><span class="admin-form-label">Options <small>(comma separated)</small></span><input name="fields[{{ $index }}][options]" value="{{ $options }}" class="admin-control w-full px-3 py-2.5" placeholder="Option one, Option two"></label>
    <input type="hidden" name="fields[{{ $index }}][unit]" value="{{ $field['unit'] ?? 'fixed' }}">
    <div class="flex items-end justify-end md:col-span-1"><button type="button" data-remove-field class="grid h-10 w-10 place-items-center rounded-xl border border-red-100 bg-red-50 text-lg font-bold text-red-500" title="Remove field">×</button></div>
</div>
