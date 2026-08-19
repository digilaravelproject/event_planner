@extends('admin.layout')

@section('content')
@php
    $selectedAttribute = old('vendor_attribute_key', $question->vendor_attribute_key);
    $selectedValues = array_map('strval', old('vendor_attribute_values', $question->vendor_attribute_values ?? []));
    $selectedMetadata = old('option_metadata', $question->option_metadata ?? []);
@endphp
<div class="admin-page mx-auto max-w-5xl">
    @include('admin.partials.module-header', [
        'title' => $question->exists ? 'Edit Event Question' : 'Add Event Question',
        'subtitle' => 'Define the answers users see, then optionally connect them to vendor data.',
    ])

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ $question->exists ? route('admin.event-questions.update', $question) : route('admin.event-questions.store') }}" enctype="multipart/form-data" class="admin-card space-y-7 p-6 sm:p-8">
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
            <div class="flex items-start gap-3">
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-extrabold text-indigo-700">1</span>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">Connect to vendor data <span class="font-medium text-slate-400">(optional)</span></h2>
                    <span class="sr-only">Dynamic vendor mapping</span>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Select a vendor attribute and its matching values. Selected values are automatically added as user-facing options below.</p>
                </div>
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
                        <p class="text-xs text-slate-400">Checking a value also adds it to the answer options.</p>
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

            @if($attributeCatalog === [])
                <p class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs text-amber-700">Add attributes and values to Dynamic Vendors before creating a vendor mapping.</p>
            @endif
        </section>

        <section class="rounded-2xl border border-rose-100 bg-rose-50/40 p-6 space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-rose-100 pb-4">
                <div>
                    <h2 class="flex items-center gap-3 text-base font-extrabold text-slate-900"><span class="flex h-8 w-8 items-center justify-center rounded-full bg-rose-100 text-sm text-[#850625]">2</span> Answer options shown to users</h2>
                    <p class="mt-1 text-xs text-slate-500">Edit the title, subtitle, icon, and card image shown to users. Every visual stays attached to its option when options are added or removed.</p>
                </div>
                <button type="button" id="add-category-option-btn" class="inline-flex items-center gap-1.5 rounded-xl bg-[#850625] px-4 py-2.5 text-xs font-extrabold text-white shadow-md hover:bg-[#6b041e] transition-all cursor-pointer">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Add option</span>
                </button>
            </div>

            <div id="category-options-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $oldCategoryOptions = old('category_options');
                    $currentOptions = $oldCategoryOptions
                        ? array_column($oldCategoryOptions, 'name')
                        : ($question->options ?? ['']);
                    $currentImages = $question->option_images ?? $question->vendor_attribute_images ?? [];
                    $currentVendorValues = $question->option_vendor_values ?? $question->vendor_attribute_values ?? [];
                    $defaultOptionIcons = ['fa-solid fa-star', 'fa-solid fa-heart', 'fa-solid fa-crown', 'fa-solid fa-leaf', 'fa-solid fa-gem', 'fa-solid fa-champagne-glasses', 'fa-solid fa-music', 'fa-solid fa-building'];
                @endphp
                @foreach($currentOptions as $optIndex => $optionText)
                    @php
                        $metadataKey = (string) (($currentVendorValues[$optIndex] ?? null) ?: $optionText);
                        $optionDetails = (array) ($selectedMetadata[$metadataKey] ?? $selectedMetadata[$optionText] ?? []);
                        $optionSubtitle = $oldCategoryOptions[$optIndex]['subtitle'] ?? $optionDetails['subtitle'] ?? '';
                        $optionIcon = $oldCategoryOptions[$optIndex]['icon'] ?? $optionDetails['icon'] ?? $defaultOptionIcons[$optIndex % count($defaultOptionIcons)];
                    @endphp
                    <div class="category-option-row rounded-2xl border border-slate-200 bg-white p-5 space-y-4 shadow-2xs hover:shadow-md transition-all relative flex flex-col justify-between">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-extrabold text-[#850625] uppercase tracking-wider">Option #<span class="option-row-number">{{ $optIndex + 1 }}</span></span>
                            <button type="button" class="remove-category-option-btn text-xs font-bold text-rose-600 hover:text-rose-800 hover:underline cursor-pointer">
                                <i class="fa-solid fa-trash-can mr-1"></i> Delete
                            </button>
                        </div>

                        <!-- Image Preview & Upload Box -->
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700">Option image <span class="font-medium text-slate-400">(optional)</span></label>
                            
                            <div class="relative h-36 w-full rounded-xl overflow-hidden bg-slate-100 border-2 border-dashed border-slate-200 group flex items-center justify-center">
                                @if(isset($currentImages[$optIndex]))
                                    <img src="{{ str_starts_with($currentImages[$optIndex], 'http') ? $currentImages[$optIndex] : asset('storage/'.ltrim($currentImages[$optIndex], '/')) }}" class="category-preview-img w-full h-full object-cover">
                                    <input type="hidden" name="category_options[{{ $optIndex }}][existing_image]" value="{{ $currentImages[$optIndex] }}">
                                @else
                                    <div class="category-preview-placeholder text-center p-3 space-y-1">
                                        <i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400"></i>
                                        <span class="block text-xs font-bold text-slate-600">Click to upload image</span>
                                        <span class="block text-[10px] text-slate-400">PNG, JPG or WEBP up to 5MB</span>
                                    </div>
                                @endif

                                <input type="file" name="category_options[{{ $optIndex }}][image]" accept="image/*" class="category-file-input absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            </div>
                        </div>

                        <!-- Category Title Input -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Option label *</label>
                            <input name="category_options[{{ $optIndex }}][name]" required value="{{ $optionText }}" placeholder="What the user will see" class="admin-control w-full px-4 py-2.5 text-sm font-semibold">
                            @if(isset($currentVendorValues[$optIndex]))
                                <input type="hidden" name="category_options[{{ $optIndex }}][vendor_value]" value="{{ $currentVendorValues[$optIndex] }}">
                            @endif
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Option subtitle <span class="font-medium text-slate-400">(optional)</span></label>
                            <textarea name="category_options[{{ $optIndex }}][subtitle]" rows="2" maxlength="500" placeholder="Short description displayed below the option title" class="admin-control w-full px-4 py-2.5 text-sm">{{ $optionSubtitle }}</textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-slate-700">Option icon</label>
                            <select name="category_options[{{ $optIndex }}][icon]" class="admin-control option-icon-select w-full px-4 py-2.5 text-sm">
                                @foreach([
                                    'fa-solid fa-star' => 'Star',
                                    'fa-solid fa-heart' => 'Heart',
                                    'fa-solid fa-crown' => 'Crown',
                                    'fa-solid fa-ring' => 'Ring',
                                    'fa-solid fa-champagne-glasses' => 'Celebration',
                                    'fa-solid fa-music' => 'Music',
                                    'fa-solid fa-utensils' => 'Food',
                                    'fa-solid fa-leaf' => 'Leaf',
                                    'fa-solid fa-building' => 'Venue',
                                    'fa-solid fa-users' => 'Guests',
                                ] as $iconClass => $iconLabel)
                                    <option value="{{ $iconClass }}" @selected($optionIcon === $iconClass)>{{ $iconLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

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
    const previouslySelectedMetadata = @json($selectedMetadata);
    const attributeSelect = document.getElementById('vendor-attribute');
    const panel = document.getElementById('vendor-values-panel');
    const valuesContainer = document.getElementById('vendor-values');
    const emptyMessage = document.getElementById('vendor-values-empty');
    const selectAll = document.getElementById('select-all-values');
    const menuCostingNote = document.getElementById('menu-costing-note');
    const optionsList = document.getElementById('category-options-list');
    const addOptionBtn = document.getElementById('add-category-option-btn');
    const selectedByAttribute = {};
    const metadataByAttribute = {};
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
        metadataByAttribute[attributeSelect.value] = previouslySelectedMetadata;
    }

    const valueCheckboxes = () => [...valuesContainer.querySelectorAll('input[name="vendor_attribute_values[]"]')];

    const previewPlaceholder = () => {
        const placeholder = document.createElement('div');
        placeholder.className = 'category-preview-placeholder text-center p-3 space-y-1';
        placeholder.innerHTML = '<i class="fa-solid fa-cloud-arrow-up text-2xl text-slate-400"></i><span class="block text-xs font-bold text-slate-600">Click to upload image</span><span class="block text-[10px] text-slate-400">PNG, JPG or WEBP up to 5MB</span>';
        return placeholder;
    };

    const reindexCategoryOptions = () => {
        optionsList.querySelectorAll('.category-option-row').forEach((row, index) => {
            row.querySelector('.option-row-number').textContent = index + 1;
            row.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/category_options\[\d+\]/, `category_options[${index}]`);
            });
        });
    };

    const bindCategoryOptionRow = row => {
        row.querySelector('.remove-category-option-btn')?.addEventListener('click', () => {
            if (optionsList.querySelectorAll('.category-option-row').length === 1) {
                row.querySelector('input[name$="[name]"]').value = '';
                return;
            }
            row.remove();
            reindexCategoryOptions();
        });

        const fileInput = row.querySelector('.category-file-input');
        fileInput?.addEventListener('change', event => {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = result => {
                const box = fileInput.parentElement;
                box.querySelector('.category-preview-placeholder')?.remove();
                let image = box.querySelector('.category-preview-img');
                if (!image) {
                    image = document.createElement('img');
                    image.className = 'category-preview-img w-full h-full object-cover';
                    box.prepend(image);
                }
                image.src = result.target.result;
            };
            reader.readAsDataURL(file);
        });
    };

    const attachVendorValue = (row, vendorValue) => {
        if (!vendorValue) return;
        let input = row.querySelector('input[name$="[vendor_value]"]');
        if (!input) {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'category_options[0][vendor_value]';
            row.querySelector('input[name$="[name]"]').after(input);
        }
        input.value = vendorValue;
    };

    const addCategoryOption = (label = '', vendorValue = '') => {
        const source = optionsList.querySelector('.category-option-row');
        if (!source) return;
        const row = source.cloneNode(true);
        row.querySelectorAll('input[type="hidden"]').forEach(input => input.remove());
        row.querySelector('input[name$="[name]"]').value = label;
        row.querySelector('textarea[name$="[subtitle]"]').value = '';
        row.querySelector('select[name$="[icon]"]').value = 'fa-solid fa-star';
        row.querySelector('.category-file-input').value = '';
        const imageBox = row.querySelector('.category-file-input').parentElement;
        imageBox.querySelector('.category-preview-img')?.remove();
        imageBox.querySelector('.category-preview-placeholder')?.remove();
        imageBox.prepend(previewPlaceholder());
        optionsList.appendChild(row);
        attachVendorValue(row, vendorValue);
        bindCategoryOptionRow(row);
        reindexCategoryOptions();
        if (!label) row.querySelector('input[name$="[name]"]').focus();
    };

    const ensureCategoryOption = (label, vendorValue) => {
        const existing = [...optionsList.querySelectorAll('input[name$="[name]"]')]
            .find(input => input.value.trim().toLocaleLowerCase() === String(label).trim().toLocaleLowerCase());
        if (existing) {
            attachVendorValue(existing.closest('.category-option-row'), vendorValue);
            reindexCategoryOptions();
            return;
        }
        const onlyInput = optionsList.querySelector('input[name$="[name]"]');
        if (optionsList.children.length === 1 && onlyInput && !onlyInput.value.trim()) {
            onlyInput.value = label;
            attachVendorValue(onlyInput.closest('.category-option-row'), vendorValue);
            reindexCategoryOptions();
        } else {
            addCategoryOption(label, vendorValue);
        }
    };

    const syncOptions = () => {
        const checkboxes = valueCheckboxes();
        const checked = checkboxes.filter(input => input.checked);
        selectedByAttribute[attributeSelect.value] = checked.map(input => input.value);
        selectAll.checked = checkboxes.length > 0 && checked.length === checkboxes.length;
        selectAll.indeterminate = checked.length > 0 && checked.length < checkboxes.length;
    };

    const renderValues = () => {
        const key = attributeSelect.value;
        const attribute = catalog[key];
        valuesContainer.replaceChildren();
        panel.classList.toggle('hidden', !key);
        menuCostingNote.classList.toggle('hidden', key !== 'menu_card_items');

        if (!key || !attribute) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
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
            checkbox.addEventListener('change', () => {
                if (checkbox.checked) ensureCategoryOption(item.label, item.value);
                syncOptions();
            });
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
        syncOptions();
    };

    attributeSelect.addEventListener('change', renderValues);
    selectAll.addEventListener('change', () => {
        valueCheckboxes().forEach(input => {
            input.checked = selectAll.checked;
            if (input.checked) ensureCategoryOption(input.dataset.label, input.value);
        });
        syncOptions();
    });

    optionsList.querySelectorAll('.category-option-row').forEach(bindCategoryOptionRow);
    addOptionBtn.addEventListener('click', () => addCategoryOption());
    reindexCategoryOptions();

    renderValues();
});
</script>
@endsection
