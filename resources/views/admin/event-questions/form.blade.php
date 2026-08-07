@extends('admin.layout')

@section('content')
@php
    $selectedAttribute = old('vendor_attribute_key', $question->vendor_attribute_key);
    $selectedValues = array_map('strval', old('vendor_attribute_values', $question->vendor_attribute_values ?? []));
    $selectedImages = array_map('strval', old('vendor_attribute_images', $question->vendor_attribute_images ?? []));
    $selectedMetadata = old('option_metadata', $question->option_metadata ?? []);
@endphp
<div class="admin-page mx-auto max-w-5xl">
    @include('admin.partials.module-header', [
        'title' => $question->exists ? 'Edit Event Question' : 'Add Event Question',
        'subtitle' => 'Configure the question and map its answers to dynamic vendor data.',
    ])

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $question->exists ? route('admin.event-questions.update', $question) : route('admin.event-questions.store') }}" class="admin-card space-y-7 p-6 sm:p-8">
        @csrf
        @if($question->exists) @method('PUT') @endif

        <section>
            <h2 class="text-base font-extrabold text-slate-900">Question details</h2>
            <div class="mt-4 grid gap-5 md:grid-cols-2">
                <label class="md:col-span-2">
                    <span class="mb-2 block text-xs font-bold">Question *</span>
                    <input name="question" required value="{{ old('question', $question->question) }}" class="admin-control w-full px-4 py-3">
                </label>
                <label>
                    <span class="mb-2 block text-xs font-bold">Question Code *</span>
                    <input name="question_code" required value="{{ old('question_code', $question->question_code) }}" class="admin-control w-full px-4 py-3 font-mono">
                </label>
                <label>
                    <span class="mb-2 block text-xs font-bold">Question Type *</span>
                    <select name="question_type" id="question-type" class="admin-control w-full px-4 py-3">
                        @foreach($types as $type)
                            <option value="{{ $type->value }}" @selected(old('question_type', $question->question_type) === $type->value)>{{ $type->label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <span class="mb-2 block text-xs font-bold">Placeholder</span>
                    <input name="placeholder" value="{{ old('placeholder', $question->placeholder) }}" class="admin-control w-full px-4 py-3">
                </label>
                <label>
                    <span class="mb-2 block text-xs font-bold">Display Order *</span>
                    <input type="number" name="display_order" min="0" value="{{ old('display_order', $question->display_order ?? 0) }}" class="admin-control w-full px-4 py-3">
                </label>
            </div>
        </section>

        <section class="rounded-2xl border border-indigo-100 bg-indigo-50/40 p-5 mt-5 mb-5">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Dynamic vendor mapping</h2>
                <p class="mt-1 text-xs leading-5 text-slate-500">Choose an attribute, then select which existing vendor values can be used as answers. These mappings can later filter matching active vendors.</p>
            </div>

            <label class="mt-4 block">
                <span class="mb-2 block text-xs font-bold text-slate-700">Vendor Attribute</span>
                <select name="vendor_attribute_key" id="vendor-attribute" class="admin-control w-full bg-white px-4 py-3">
                    <option value="">No vendor mapping</option>
                    @foreach($attributeCatalog as $attribute)
                        <option value="{{ $attribute['key'] }}" @selected($selectedAttribute === $attribute['key'])>{{ $attribute['label'] }} ({{ count($attribute['values']) }} values)</option>
                    @endforeach
                </select>
            </label>

            <div id="vendor-values-panel" class="mt-4 hidden rounded-xl border border-slate-200 bg-white p-4">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
                    <div>
                        <p id="vendor-values-title" class="text-sm font-extrabold text-slate-800">Available values</p>
                        <p class="text-xs text-slate-400">Vendor count is shown beside each value.</p>
                    </div>
                    <label class="flex items-center gap-2 text-xs font-bold text-[#3950a2]">
                        <input type="checkbox" id="select-all-values" class="h-4 w-4 rounded border-slate-300">
                        Select all
                    </label>
                </div>
                <div id="menu-costing-note" class="mt-3 hidden rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-800">Set a food category and per-guest cost for every menu item you make available. These prices are shown to users and used in their generated catering plan.</div>
                <div id="vendor-values" class="mt-4 grid max-h-72 gap-2 overflow-y-auto sm:grid-cols-2"></div>
                <p id="vendor-values-empty" class="hidden py-5 text-center text-sm text-slate-400">No populated values exist for this attribute yet.</p>
            </div>

            <div id="vendor-images-panel" class="mt-4 hidden rounded-xl border border-slate-200 bg-white p-4">
                <div class="border-b border-slate-100 pb-3">
                    <p class="text-sm font-extrabold text-slate-800">Attribute images</p>
                    <p class="text-xs text-slate-400">Select the images that should be available with this question.</p>
                </div>
                <div id="vendor-images" class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3"></div>
            </div>

            @if($attributeCatalog === [])
                <p class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-700">Add attributes and values to Dynamic Vendors before creating a vendor mapping.</p>
            @endif
        </section>

        <label id="options-wrap" class="block">
            <span class="mb-2 block text-xs font-bold">Options <small class="font-normal text-slate-400">(one per line)</small></span>
            <textarea name="options_text" id="options-text" rows="6" class="admin-control w-full px-4 py-3">{{ old('options_text', implode("\n", $question->options ?? [])) }}</textarea>
            <span id="mapped-options-note" class="mt-2 hidden text-xs text-indigo-600">Options are synchronized from the selected vendor values.</span>
        </label>

        <div class="flex gap-6">
            <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="is_required" value="1" @checked(old('is_required', $question->is_required))> Required</label>
            <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="status" value="1" @checked(old('status', $question->status ?? true))> Enabled</label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.event-questions.index') }}" class="rounded-xl border px-5 py-2.5 text-sm font-bold">Cancel</a>
            <button class="admin-primary-button rounded-xl px-6 py-2.5 text-sm font-bold text-white">Save Question</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const catalog = @json($attributeCatalog);
    const previouslySelected = @json($selectedValues);
    const previouslySelectedImages = @json($selectedImages);
    const previouslySelectedMetadata = @json($selectedMetadata);
    const attributeSelect = document.getElementById('vendor-attribute');
    const panel = document.getElementById('vendor-values-panel');
    const valuesContainer = document.getElementById('vendor-values');
    const emptyMessage = document.getElementById('vendor-values-empty');
    const selectAll = document.getElementById('select-all-values');
    const optionsText = document.getElementById('options-text');
    const mappedNote = document.getElementById('mapped-options-note');
    const imagesPanel = document.getElementById('vendor-images-panel');
    const imagesContainer = document.getElementById('vendor-images');
    const menuCostingNote = document.getElementById('menu-costing-note');
    let selectedByAttribute = {};
    let selectedImagesByAttribute = {};
    let metadataByAttribute = {};
    const vendorTooltip = document.createElement('div');
    vendorTooltip.className = 'pointer-events-none fixed z-[100] hidden max-w-xs rounded-lg bg-slate-900 px-3 py-2 text-xs font-semibold leading-5 text-white shadow-xl';
    vendorTooltip.setAttribute('role', 'tooltip');
    document.body.appendChild(vendorTooltip);

    const showVendorTooltip = target => {
        const names = target.dataset.vendorNames;
        if (!names) return;
        vendorTooltip.textContent = names;
        vendorTooltip.classList.remove('hidden');
        const rect = target.getBoundingClientRect();
        const left = Math.min(window.innerWidth - vendorTooltip.offsetWidth - 12, Math.max(12, rect.left + rect.width / 2 - vendorTooltip.offsetWidth / 2));
        const top = Math.max(12, rect.top - vendorTooltip.offsetHeight - 8);
        vendorTooltip.style.left = `${left}px`;
        vendorTooltip.style.top = `${top}px`;
    };
    const hideVendorTooltip = () => vendorTooltip.classList.add('hidden');

    if (attributeSelect.value) {
        selectedByAttribute[attributeSelect.value] = previouslySelected;
        selectedImagesByAttribute[attributeSelect.value] = previouslySelectedImages;
        metadataByAttribute[attributeSelect.value] = previouslySelectedMetadata;
    }

    const valueCheckboxes = () => [...valuesContainer.querySelectorAll('input[name="vendor_attribute_values[]"]')];

    const syncOptions = () => {
        const checkboxes = valueCheckboxes();
        const checked = checkboxes.filter(input => input.checked);
        selectedByAttribute[attributeSelect.value] = checked.map(input => input.value);
        optionsText.value = checked.map(input => input.dataset.label).join('\n');
        optionsText.readOnly = attributeSelect.value !== '';
        mappedNote.classList.toggle('hidden', attributeSelect.value === '');
        selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
        selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
    };

    const renderValues = () => {
        const key = attributeSelect.value;
        const attribute = catalog[key];
        valuesContainer.replaceChildren();
        imagesContainer.replaceChildren();
        panel.classList.toggle('hidden', !key);
        menuCostingNote.classList.toggle('hidden', key !== 'menu_card_items');
        optionsText.readOnly = Boolean(key);
        mappedNote.classList.toggle('hidden', !key);

        if (!key || !attribute) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
            imagesPanel.classList.add('hidden');
            return;
        }

        document.getElementById('vendor-values-title').textContent = `${attribute.label} values`;
        const selected = new Set(selectedByAttribute[key] || []);
        attribute.values.forEach(item => {
            const row = document.createElement('div');
            row.className = 'rounded-lg border border-slate-100 px-3 py-2.5 text-sm transition hover:border-indigo-200 hover:bg-indigo-50/40';

            const top = document.createElement('div');
            top.className = 'flex items-center justify-between gap-3';

            const left = document.createElement('label');
            left.className = 'flex min-w-0 items-center gap-2';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'vendor_attribute_values[]';
            checkbox.value = item.value;
            checkbox.dataset.label = item.label;
            checkbox.checked = selected.has(String(item.value));
            checkbox.className = 'h-4 w-4 rounded border-slate-300';
            checkbox.addEventListener('change', syncOptions);
            const value = document.createElement('span');
            value.className = 'truncate font-medium text-slate-700';
            value.textContent = item.label;
            left.append(checkbox, value);

            const count = document.createElement('span');
            count.className = 'shrink-0 cursor-help rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-500 outline-none focus:ring-2 focus:ring-indigo-300';
            count.textContent = `${item.vendors_count} vendor${item.vendors_count === 1 ? '' : 's'}`;
            const vendorNames = (item.vendor_names || []).join(', ');
            count.title = vendorNames;
            count.dataset.vendorNames = vendorNames;
            count.tabIndex = 0;
            count.setAttribute('aria-label', `${count.textContent}: ${vendorNames}`);
            count.addEventListener('mouseenter', () => showVendorTooltip(count));
            count.addEventListener('mouseleave', hideVendorTooltip);
            count.addEventListener('focus', () => showVendorTooltip(count));
            count.addEventListener('blur', hideVendorTooltip);
            top.append(left, count);
            row.appendChild(top);

            if (key === 'menu_card_items') {
                const saved = metadataByAttribute[key]?.[String(item.value)] || {};
                const fields = document.createElement('div');
                fields.className = 'mt-3 grid grid-cols-2 gap-2 border-t border-slate-100 pt-3';

                const valueInput = document.createElement('input');
                valueInput.type = 'hidden';
                valueInput.name = 'option_metadata_values[]';
                valueInput.value = item.value;

                const category = document.createElement('input');
                category.name = 'option_metadata_categories[]';
                category.value = saved.category || 'Menu Items';
                category.placeholder = 'Category (e.g. Main Course)';
                category.className = 'admin-control min-w-0 px-2.5 py-2 text-xs';

                const cost = document.createElement('input');
                cost.type = 'number';
                cost.name = 'option_metadata_costs[]';
                cost.value = saved.cost ?? 0;
                cost.min = '0.01';
                cost.step = '0.01';
                cost.placeholder = 'Cost per person';
                cost.className = 'admin-control min-w-0 px-2.5 py-2 text-xs';

                const rememberMetadata = () => {
                    metadataByAttribute[key] ||= {};
                    metadataByAttribute[key][String(item.value)] = { category: category.value, cost: cost.value };
                };
                category.addEventListener('input', rememberMetadata);
                cost.addEventListener('input', rememberMetadata);
                fields.append(valueInput, category, cost);
                row.appendChild(fields);
            }

            valuesContainer.appendChild(row);
        });

        emptyMessage.classList.toggle('hidden', attribute.values.length > 0);
        const selectedImages = new Set(selectedImagesByAttribute[key] || []);
        (attribute.images || []).forEach(image => {
            const label = document.createElement('label');
            label.className = 'overflow-hidden rounded-xl border border-slate-200 bg-white p-2';
            const preview = document.createElement('img');
            preview.src = `{{ asset('storage') }}/${image}`;
            preview.alt = `${attribute.label} image`;
            preview.className = 'h-28 w-full rounded-lg object-cover';
            const choice = document.createElement('span');
            choice.className = 'mt-2 flex items-center gap-2 text-xs font-bold text-slate-600';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.name = 'vendor_attribute_images[]';
            checkbox.value = image;
            checkbox.checked = selectedImages.has(image);
            checkbox.addEventListener('change', () => {
                selectedImagesByAttribute[key] = [...imagesContainer.querySelectorAll('input:checked')].map(input => input.value);
            });
            choice.append(checkbox, document.createTextNode('Use image'));
            label.append(preview, choice);
            imagesContainer.appendChild(label);
        });
        imagesPanel.classList.toggle('hidden', !attribute.images || attribute.images.length === 0);
        syncOptions();
    };

    attributeSelect.addEventListener('change', renderValues);
    selectAll.addEventListener('change', () => {
        valueCheckboxes().forEach(input => input.checked = selectAll.checked);
        syncOptions();
    });
    renderValues();
});
</script>
@endsection
