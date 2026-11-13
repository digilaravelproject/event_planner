@php
    $document = $vendor?->vendor_json ?? [];
    $routePrefix = $routePrefix ?? 'admin.dynamic-vendors';
    $defaultStatus = str_starts_with($routePrefix, 'admin.') ? 'active' : 'draft';
    $categoryCatalog = \App\Modules\DynamicVendors\Support\VendorCategoryFieldCatalog::all();
    $selectedCategory = old('category', data_get($document, 'identity.category', ''));
    $savedAttributes = old('attributes', collect(data_get($document, 'attributes', []))->map(function ($attribute) {
        $validation = $attribute['validation'] ?? [];
        $value = $attribute['value'] ?? '';
        return array_merge($attribute, $validation, [
            'value' => is_array($value) ? implode(', ', $value) : $value,
            'allowed_values' => implode(', ', $validation['allowed_values'] ?? []),
            'pricing_unit' => data_get($attribute, 'pricing.unit', 'fixed'),
            'pricing_rate' => data_get($attribute, 'pricing.rate', ''),
        ]);
    })->all());
    $existingImages = old('existing_images', data_get($document, 'media.images', []));
@endphp

<div class="admin-page vendor-wizard mx-auto max-w-6xl">
    @include('admin.partials.alerts')

    <div class="admin-hero mb-5 flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="relative z-10">
            <a href="{{ route($routePrefix.'.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-rose-100 transition hover:text-white">← Vendor list</a>
            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-white">{{ $title }}</h1>
            <p class="mt-1 text-xs text-rose-100">Start with the vendor name and category. The right questions will appear automatically.</p>
        </div>
        <div class="relative z-10 flex items-center gap-2 text-[11px] font-bold text-amber-200"><span class="grid h-7 w-7 place-items-center rounded-full border border-amber-300/40">✓</span> Guided setup</div>
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-2xl border border-rose-400/30 bg-rose-950/40 p-4 text-sm text-rose-100">
            <p class="font-bold">Please review this information.</p>
            <ul class="mt-2 list-disc pl-5"><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="vendor-form" class="space-y-5">
        @csrf
        @if($method !== 'POST') @method($method) @endif
        <input type="hidden" name="status" value="{{ old('status', $vendor?->status ?? $defaultStatus) }}">

        <section class="vendor-wizard-card p-5 sm:p-6">
            <div class="mb-5 flex items-start gap-3">
                <span class="vendor-step">1</span>
                <div><h2 class="text-base font-extrabold text-white">Who is the vendor?</h2><p class="mt-1 text-xs text-stone-300">Only these two details are needed to prepare the correct form.</p></div>
            </div>
            <div class="grid gap-5 md:grid-cols-2">
                <label class="block"><span class="vendor-label">Vendor name *</span><input name="name" required maxlength="255" autocomplete="organization" value="{{ old('name', data_get($document, 'identity.name')) }}" placeholder="Example: Royal Orchid Banquet" class="vendor-input"></label>
                <label class="block"><span class="vendor-label">Category *</span><select id="vendor-category" name="category" required class="vendor-input"><option value="">Select the service category</option>@if($selectedCategory && !array_key_exists($selectedCategory, $categoryCatalog))<option value="{{ $selectedCategory }}" selected>{{ $selectedCategory }}</option>@endif @foreach(array_keys($categoryCatalog) as $category)<option value="{{ $category }}" @selected($selectedCategory === $category)>{{ $category }}</option>@endforeach</select><span class="vendor-help">Choose one category to display only relevant questions.</span></label>
            </div>
        </section>

        <div id="category-empty" class="vendor-empty-state {{ $selectedCategory ? 'hidden' : '' }}">
            <span class="text-2xl text-amber-300">✦</span>
            <p class="mt-2 font-bold text-stone-200">Select a category to continue</p>
            <p class="mt-1 text-xs text-stone-400">The rest of the form is created for that type of vendor.</p>
        </div>

        <div id="category-form" class="{{ $selectedCategory ? '' : 'hidden' }} space-y-5">
            <section class="vendor-wizard-card p-5 sm:p-6">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex items-start gap-3"><span class="vendor-step">2</span><div><h2 id="category-heading" class="text-base font-extrabold text-white">{{ $selectedCategory ?: 'Vendor' }} information</h2><p class="mt-1 text-xs text-stone-300">Use everyday information. Technical settings are handled automatically.</p></div></div>
                </div>

                <div id="guided-fields" class="grid gap-4 md:grid-cols-2">
                    @foreach($savedAttributes as $index => $attribute)
                        @php
                            $type = $attribute['type'] ?? 'text';
                            $allowed = array_values(array_filter(array_map('trim', explode(',', (string) ($attribute['allowed_values'] ?? '')))));
                            $value = $attribute['value'] ?? '';
                        @endphp
                        <div class="guided-field" data-guided-field>
                            <input type="hidden" name="attributes[{{ $index }}][id]" value="{{ $attribute['id'] ?? '' }}">
                            <input type="hidden" name="attributes[{{ $index }}][label]" value="{{ $attribute['label'] ?? '' }}">
                            <input type="hidden" name="attributes[{{ $index }}][type]" value="{{ $type }}">
                            <input type="hidden" name="attributes[{{ $index }}][required]" value="0">
                            <input type="hidden" name="attributes[{{ $index }}][allowed_values]" value="{{ $attribute['allowed_values'] ?? '' }}">
                            @foreach($attribute['images'] ?? [] as $image)<input type="hidden" name="existing_attribute_images[{{ $index }}][]" value="{{ $image }}">@endforeach
                            <span class="vendor-label">{{ $attribute['label'] ?? 'Information' }}</span>
                            @if($type === 'textarea' || $type === 'multi_select')
                                <textarea name="attributes[{{ $index }}][value]" rows="2" class="vendor-input" placeholder="Enter {{ strtolower($attribute['label'] ?? 'information') }}">{{ $value }}</textarea>
                            @elseif($type === 'dropdown' && count($allowed))
                                <select name="attributes[{{ $index }}][value]" class="vendor-input"><option value="">Select an option</option>@foreach($allowed as $option)<option value="{{ $option }}" @selected((string) $value === $option)>{{ $option }}</option>@endforeach</select>
                            @elseif($type === 'boolean')
                                <select name="attributes[{{ $index }}][value]" class="vendor-input"><option value="0" @selected(!$value)>No</option><option value="1" @selected((bool) $value)>Yes</option></select>
                            @elseif(in_array($type, ['image', 'file', 'video'], true))
                                <input type="hidden" name="attributes[{{ $index }}][value]" value="{{ $value }}"><div class="rounded-xl border border-white/10 bg-black/20 px-3 py-3 text-xs text-stone-300">Existing uploaded file will be kept.</div>
                            @else
                                <input type="{{ in_array($type, ['number', 'currency'], true) ? 'number' : ($type === 'email' ? 'email' : ($type === 'phone' ? 'tel' : 'text')) }}" @if(in_array($type, ['number', 'currency'], true)) step="0.01" min="0" @endif name="attributes[{{ $index }}][value]" value="{{ $value }}" class="vendor-input">
                            @endif
                            @if($type === 'currency' && ($attribute['pricing_rate'] ?? '') === '')
                                <input type="hidden" name="attributes[{{ $index }}][pricing_unit]" value="{{ $attribute['pricing_unit'] ?? 'fixed' }}">
                            @endif
                            <button type="button" class="remove-guided-field mt-2 text-[11px] font-bold text-rose-300 hover:text-rose-200">Remove this field</button>
                            @if(($attribute['pricing_rate'] ?? '') !== '')
                                <details class="mt-3 rounded-xl border border-amber-300/15 bg-black/15 p-3">
                                    <summary class="cursor-pointer text-[11px] font-bold text-amber-200">Planning cost</summary>
                                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                        <label><span class="vendor-label">Price used in estimates</span><input type="number" min="0" step="0.01" name="attributes[{{ $index }}][pricing_rate]" value="{{ $attribute['pricing_rate'] }}" class="vendor-input"></label>
                                        <label><span class="vendor-label">Charged by</span><select name="attributes[{{ $index }}][pricing_unit]" class="vendor-input">@foreach(['fixed' => 'One fixed amount', 'per_guest' => 'Per guest', 'per_unit' => 'Per item', 'per_hour' => 'Per hour', 'per_day' => 'Per day'] as $unit => $unitLabel)<option value="{{ $unit }}" @selected(($attribute['pricing_unit'] ?? 'fixed') === $unit)>{{ $unitLabel }}</option>@endforeach</select></label>
                                    </div>
                                </details>
                            @endif
                        </div>
                    @endforeach
                </div>

                <button id="add-simple-field" type="button" class="mt-5 inline-flex items-center gap-2 rounded-xl border border-amber-300/25 px-4 py-2.5 text-xs font-bold text-amber-200 transition hover:bg-amber-300/10">+ Add another detail</button>
                <!-- Optional validation is managed automatically and is intentionally not displayed. -->
            </section>

            <section class="vendor-wizard-card p-5 sm:p-6">
                <div class="mb-5 flex items-start gap-3"><span class="vendor-step">3</span><div><h2 class="text-base font-extrabold text-white">Photos and description</h2><p class="mt-1 text-xs text-stone-300">Optional information that helps customers understand the service.</p></div></div>
                @if(count($existingImages))
                    <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">@foreach($existingImages as $image)<label class="rounded-xl border border-white/10 bg-black/20 p-2"><img src="{{ asset('storage/'.$image) }}" alt="Vendor" class="h-24 w-full rounded-lg object-cover"><span class="mt-2 flex items-center gap-2 text-[11px] text-stone-300"><input type="checkbox" name="existing_images[]" value="{{ $image }}" checked> Keep photo</span></label>@endforeach</div>
                @endif
                <div class="grid gap-5 md:grid-cols-2">
                    <label class="block md:col-span-2"><span class="vendor-label">Vendor photos</span><input type="file" name="images[]" multiple accept="image/*" class="vendor-file"><span class="vendor-help">Choose clear JPG, PNG or WebP photos. You can select more than one.</span></label>
                    <label class="block md:col-span-2"><span class="vendor-label">Short introduction</span><textarea name="short_description" rows="2" maxlength="1000" class="vendor-input" placeholder="A short sentence shown in vendor summaries">{{ old('short_description', data_get($document, 'seo.short_description')) }}</textarea></label>
                    <label class="block md:col-span-2"><span class="vendor-label">Full description</span><textarea name="description" rows="4" class="vendor-input" placeholder="Describe the experience, specialities and what customers can expect">{{ old('description', data_get($document, 'seo.description')) }}</textarea></label>
                    <input type="hidden" name="tags" value="{{ old('tags', implode(', ', data_get($document, 'seo.tags', []))) }}">
                    <input type="hidden" name="keywords" value="{{ old('keywords', implode(', ', data_get($document, 'seo.keywords', []))) }}">
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-end">
                <a href="{{ route($routePrefix.'.index') }}" class="rounded-xl border border-white/15 px-5 py-3 text-center text-sm font-bold text-stone-200">Cancel</a>
                <button class="admin-primary-button rounded-xl px-7 py-3 text-sm font-bold text-white">{{ $vendor ? 'Save Vendor Changes' : 'Create Vendor' }}</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const catalog = @json($categoryCatalog);
    const category = document.getElementById('vendor-category');
    const form = document.getElementById('category-form');
    const empty = document.getElementById('category-empty');
    const fields = document.getElementById('guided-fields');
    const heading = document.getElementById('category-heading');
    const isEditing = @json((bool) $vendor);
    let initialCategory = @json($selectedCategory);

    const escapeHtml = value => String(value ?? '').replace(/[&<>'"]/g, character => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[character]));
    const inputFor = (field, index) => {
        const base = `attributes[${index}]`;
        const options = field.options || [];
        let input;
        if (field.type === 'textarea' || field.type === 'multi_select') {
            input = `<textarea name="${base}[value]" rows="2" class="vendor-input" placeholder="${escapeHtml(field.help)}"></textarea>`;
        } else if (field.type === 'dropdown') {
            input = `<select name="${base}[value]" class="vendor-input"><option value="">Select an option</option>${options.map(option => `<option value="${escapeHtml(option)}">${escapeHtml(option)}</option>`).join('')}</select>`;
        } else if (field.type === 'boolean') {
            input = `<select name="${base}[value]" class="vendor-input"><option value="0">No</option><option value="1">Yes</option></select>`;
        } else {
            const type = ['number', 'currency'].includes(field.type) ? 'number' : (field.type === 'email' ? 'email' : (field.type === 'phone' ? 'tel' : 'text'));
            input = `<input type="${type}" ${['number', 'currency'].includes(field.type) ? 'step="0.01" min="0"' : ''} name="${base}[value]" class="vendor-input" placeholder="${escapeHtml(field.help)}">`;
        }
        const pricing = field.type === 'currency' ? `<input type="hidden" name="${base}[pricing_unit]" value="${escapeHtml(field.unit || 'fixed')}">` : '';
        return `<div class="guided-field" data-guided-field><input type="hidden" name="${base}[label]" value="${escapeHtml(field.label)}"><input type="hidden" name="${base}[type]" value="${escapeHtml(field.type)}"><input type="hidden" name="${base}[required]" value="0"><input type="hidden" name="${base}[allowed_values]" value="${escapeHtml(options.join(', '))}"><span class="vendor-label">${escapeHtml(field.label)}</span>${input}${pricing}<span class="vendor-help">${escapeHtml(field.help)}</span><button type="button" class="remove-guided-field mt-2 text-[11px] font-bold text-rose-300 hover:text-rose-200">Remove this field</button></div>`;
    };
    const reindex = () => [...fields.querySelectorAll('[data-guided-field]')].forEach((row, index) => row.querySelectorAll('[name]').forEach(input => input.name = input.name.replace(/attributes\[\d+\]/, `attributes[${index}]`)));
    const bindRemove = () => document.querySelectorAll('.remove-guided-field').forEach(button => button.onclick = () => { button.closest('[data-guided-field]').remove(); reindex(); });
    const loadCategory = (name, replace = true) => {
        empty.classList.toggle('hidden', Boolean(name));
        form.classList.toggle('hidden', !name);
        heading.textContent = `${name || 'Vendor'} information`;
        if (name && replace) fields.innerHTML = (catalog[name] || []).map(inputFor).join('');
        bindRemove();
    };

    category.addEventListener('change', () => {
        const hasData = fields.querySelectorAll('[data-guided-field]').length > 0;
        if (isEditing && hasData && initialCategory && category.value !== initialCategory && !confirm('Changing the category will replace the current detail fields with the new category form. Continue?')) {
            category.value = initialCategory;
            return;
        }
        loadCategory(category.value, true);
        initialCategory = category.value;
    });

    document.getElementById('add-simple-field').addEventListener('click', async () => {
        let label = '';
        if (window.Swal) {
            const result = await window.Swal.fire({
                title: 'Add another detail',
                text: 'Enter a clear name for the information you want to record.',
                input: 'text',
                inputPlaceholder: 'Example: Award received',
                showCancelButton: true,
                confirmButtonText: 'Add detail',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#850625',
                cancelButtonColor: '#403538',
                background: '#1b1114',
                color: '#ffffff',
                inputValidator: value => value?.trim() ? undefined : 'Please enter a detail name.'
            });
            if (!result.isConfirmed) return;
            label = result.value;
        } else return;
        if (!label || !label.trim()) return;
        fields.insertAdjacentHTML('beforeend', inputFor({label: label.trim(), type: 'text', help: `Enter ${label.trim().toLowerCase()}`, options: []}, fields.children.length));
        bindRemove(); reindex(); fields.lastElementChild.querySelector('input:not([type=hidden]), textarea, select')?.focus();
    });

    loadCategory(category.value, fields.children.length === 0);
});
</script>
