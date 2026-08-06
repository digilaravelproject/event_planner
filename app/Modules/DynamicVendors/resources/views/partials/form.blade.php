@php
    $document = $vendor?->vendor_json ?? [];
    $savedAttributes = data_get($document, 'attributes', []);
    $blankAttribute = [[
        'id' => '', 'label' => '', 'type' => 'text', 'value' => '', 'required' => false,
        'min_length' => '', 'max_length' => '', 'min_value' => '', 'max_value' => '',
        'allowed_values' => '', 'default_value' => '',
    ]];
    $formAttributes = old('attributes', collect($savedAttributes)->map(function ($attribute) {
        $validation = $attribute['validation'] ?? [];
        $value = $attribute['value'] ?? '';
        if (is_array($value)) $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        return array_merge($attribute, $validation, [
            'value' => $value,
            'allowed_values' => implode(', ', $validation['allowed_values'] ?? []),
        ]);
    })->all());
    if (count($formAttributes) === 0) $formAttributes = $blankAttribute;
    $existingImages = old('existing_images', data_get($document, 'media.images', []));
@endphp

<div class="admin-page">
    @include('admin.partials.alerts')
    <div class="admin-hero mb-6 flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between sm:p-8">
        <div class="relative z-10">
            <a href="{{ route('admin.dynamic-vendors.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-100 transition hover:text-white">← Dynamic Vendors</a>
            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-white sm:text-3xl">{{ $title }}</h1>
            <p class="mt-1 text-sm text-blue-100">{{ $subtitle }}</p>
        </div>
        @if($vendor)
            <a href="{{ route('admin.dynamic-vendors.show', $vendor) }}" class="relative z-10 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-bold text-white backdrop-blur transition hover:bg-white/20">View record</a>
        @endif
    </div>

    @if($errors->any())
        <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <p class="font-bold">Please correct the highlighted information.</p>
            <ul class="mt-2 list-disc pl-5"><li>{{ $errors->first() }}</li></ul>
        </div>
    @endif

    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="vendor-form" class="space-y-5">
        @csrf
        @if($method !== 'POST') @method($method) @endif

        <div class="admin-card flex gap-2 overflow-x-auto p-2" role="tablist">
            @foreach(['details' => 'Vendor Details', 'attributes' => 'Dynamic Attributes', 'media' => 'Images', 'seo' => 'SEO'] as $tab => $label)
                <button type="button" data-tab-button="{{ $tab }}" class="tab-button whitespace-nowrap rounded-xl px-4 py-2.5 text-sm font-bold {{ $loop->first ? 'bg-[#3950a2] text-white' : 'text-slate-600 hover:bg-slate-50' }}">{{ $label }}</button>
            @endforeach
        </div>

        <section data-tab-panel="details" class="admin-card tab-panel p-6">
            <div class="mb-5"><h2 class="text-lg font-extrabold text-slate-900">Vendor details</h2><p class="text-sm text-slate-500">Name and category are also stored only inside the JSON document.</p></div>
            <div class="grid gap-5 md:grid-cols-3">
                <label class="block md:col-span-1"><span class="mb-2 block text-xs font-bold text-slate-700">Vendor name *</span><input name="name" required maxlength="255" value="{{ old('name', data_get($document, 'identity.name')) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#3950a2] focus:outline-none focus:ring-2 focus:ring-[#3950a2]/10"></label>
                <label class="block md:col-span-1"><span class="mb-2 block text-xs font-bold text-slate-700">Category *</span><input name="category" list="category-suggestions" required maxlength="255" value="{{ old('category', data_get($document, 'identity.category')) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#3950a2] focus:outline-none focus:ring-2 focus:ring-[#3950a2]/10"><datalist id="category-suggestions">@foreach($categorySuggestions as $suggestion)<option value="{{ $suggestion }}">@endforeach</datalist></label>
                <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">Status *</span><select name="status" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">@foreach(['draft', 'active', 'inactive', 'archived'] as $status)<option value="{{ $status }}" @selected(old('status', $vendor?->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
            </div>
        </section>

        <section data-tab-panel="attributes" class="admin-card tab-panel hidden p-6">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div><h2 class="text-lg font-extrabold text-slate-900">Dynamic attributes</h2><p class="text-sm text-slate-500">Drag rows to reorder. Names are unrestricted.</p></div>
                <div class="flex flex-wrap gap-2">
                    <input id="attribute-search" type="search" placeholder="Search attributes" class="w-44 rounded-xl border border-slate-200 px-3 py-2 text-sm">
                    <a href="{{ route('admin.dynamic-vendors.attribute-sheet.sample') }}" class="inline-flex items-center rounded-xl border border-[#3950a2]/20 bg-indigo-50 px-4 py-2 text-sm font-bold text-[#3950a2] transition hover:bg-indigo-100">Download Sample Sheet</a>
                    <button type="button" id="upload-attribute-sheet" data-import-url="{{ route('admin.dynamic-vendors.attribute-sheet.import') }}" class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-bold text-emerald-700 transition hover:bg-emerald-100">Upload Attribute Sheet</button>
                    <input id="attribute-sheet-file" type="file" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="hidden">
                    <button type="button" id="add-attribute" class="rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5">+ Add Attribute</button>
                </div>
            </div>
            <div id="attribute-sheet-message" class="mb-4 hidden rounded-xl border px-4 py-3 text-sm" role="status" aria-live="polite"></div>
            <datalist id="attribute-suggestions">@foreach($attributeSuggestions as $suggestion)<option value="{{ $suggestion }}">@endforeach</datalist>
            <div id="attribute-list" class="space-y-4">
                @foreach($formAttributes as $index => $attribute)
                    @include('dynamic-vendors::partials.attribute-row', ['index' => $index, 'attribute' => $attribute])
                @endforeach
            </div>
            <div id="empty-attributes" class="hidden rounded-xl border border-dashed border-slate-300 p-8 text-center text-sm text-slate-500">No attributes yet. Add one whenever you are ready.</div>
        </section>

        <section data-tab-panel="media" class="admin-card tab-panel hidden p-6">
            <h2 class="text-lg font-extrabold text-slate-900">Vendor images</h2>
            <p class="mb-5 text-sm text-slate-500">Upload multiple images. Their public storage paths are recorded in JSON.</p>
            @if(count($existingImages))
                <div class="mb-5 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($existingImages as $image)
                        <label class="relative overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-2">
                            <img src="{{ asset('storage/'.$image) }}" alt="Vendor image" class="h-32 w-full rounded-lg object-cover">
                            <span class="mt-2 flex items-center gap-2 text-xs font-bold text-slate-600"><input type="checkbox" name="existing_images[]" value="{{ $image }}" checked> Keep image</span>
                        </label>
                    @endforeach
                </div>
            @endif
            <label class="block rounded-xl border-2 border-dashed border-slate-300 p-8 text-center"><span class="block text-sm font-bold text-slate-700">Choose images</span><span class="mt-1 block text-xs text-slate-500">JPG, PNG, GIF or WebP · up to 10 MB each</span><input type="file" name="images[]" accept="image/*" multiple class="mt-4 text-sm"></label>
        </section>

        <section data-tab-panel="seo" class="admin-card tab-panel hidden p-6">
            <h2 class="text-lg font-extrabold text-slate-900">Descriptions & discovery</h2>
            <div class="mt-5 grid gap-5 md:grid-cols-2">
                <label class="block md:col-span-2"><span class="mb-2 block text-xs font-bold text-slate-700">Short description</span><textarea name="short_description" rows="2" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('short_description', data_get($document, 'seo.short_description')) }}</textarea></label>
                <label class="block md:col-span-2"><span class="mb-2 block text-xs font-bold text-slate-700">Description</span><textarea name="description" rows="5" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm">{{ old('description', data_get($document, 'seo.description')) }}</textarea></label>
                <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">Tags <span class="font-normal text-slate-400">(comma-separated)</span></span><input name="tags" value="{{ old('tags', implode(', ', data_get($document, 'seo.tags', []))) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"></label>
                <label class="block"><span class="mb-2 block text-xs font-bold text-slate-700">Keywords <span class="font-normal text-slate-400">(comma-separated)</span></span><input name="keywords" value="{{ old('keywords', implode(', ', data_get($document, 'seo.keywords', []))) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm"></label>
            </div>
        </section>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.dynamic-vendors.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-700">Cancel</a>
            <button class="admin-primary-button rounded-xl px-6 py-2.5 text-sm font-bold text-white">{{ $vendor ? 'Save New Version' : 'Create Vendor' }}</button>
        </div>
    </form>
</div>

<template id="attribute-template">
    @include('dynamic-vendors::partials.attribute-row', ['index' => '__INDEX__', 'attribute' => $blankAttribute[0]])
</template>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('attribute-list');
    const template = document.getElementById('attribute-template');
    const empty = document.getElementById('empty-attributes');
    const sheetButton = document.getElementById('upload-attribute-sheet');
    const sheetInput = document.getElementById('attribute-sheet-file');
    const sheetMessage = document.getElementById('attribute-sheet-message');
    let dragged = null;

    const reindex = () => {
        [...list.querySelectorAll('.attribute-row')].forEach((row, index) => {
            row.dataset.index = index;
            row.querySelectorAll('[name]').forEach(input => input.name = input.name.replace(/attributes\[[^\]]+\]|attribute_uploads\[[^\]]+\]|attribute_images\[[^\]]+\]|existing_attribute_images\[[^\]]+\]/, match => {
                if (match.startsWith('attribute_uploads')) return `attribute_uploads[${index}]`;
                if (match.startsWith('attribute_images')) return `attribute_images[${index}]`;
                if (match.startsWith('existing_attribute_images')) return `existing_attribute_images[${index}]`;
                return `attributes[${index}]`;
            }));
            row.querySelector('.position-label').textContent = `Attribute ${index + 1}`;
        });
        empty.classList.toggle('hidden', list.children.length > 0);
    };

    const bindRow = row => {
        row.querySelector('.remove-attribute').addEventListener('click', () => { row.remove(); reindex(); });
        row.querySelector('.duplicate-attribute').addEventListener('click', () => {
            const clone = row.cloneNode(true);
            const id = clone.querySelector('[data-attribute-id]'); if (id) id.value = '';
            clone.querySelectorAll('input[type=file]').forEach(input => input.value = '');
            list.insertBefore(clone, row.nextSibling); bindRow(clone); reindex();
        });
        row.addEventListener('dragstart', () => { dragged = row; row.classList.add('opacity-50'); });
        row.addEventListener('dragend', () => { dragged = null; row.classList.remove('opacity-50'); reindex(); });
        row.addEventListener('dragover', event => { event.preventDefault(); if (dragged && dragged !== row) list.insertBefore(dragged, row.getBoundingClientRect().top + row.offsetHeight / 2 < event.clientY ? row.nextSibling : row); });
        row.querySelector('.attribute-type').addEventListener('change', event => {
            const upload = row.querySelector('.attribute-upload');
            const value = row.querySelector('.attribute-value');
            const isUpload = ['image', 'file', 'video'].includes(event.target.value);
            upload.classList.toggle('hidden', !isUpload);
            value.classList.toggle('hidden', isUpload);
        });
    };

    [...list.querySelectorAll('.attribute-row')].forEach(bindRow);
    const appendAttribute = attribute => {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.replaceAll('__INDEX__', list.children.length).trim();
        const row = wrapper.firstElementChild;
        row.querySelector('.attribute-name').value = attribute.label ?? '';
        row.querySelector('.attribute-type').value = attribute.type ?? 'text';
        row.querySelector('.attribute-value textarea').value = attribute.value ?? '';
        list.appendChild(row);
        bindRow(row);
        row.querySelector('.attribute-type').dispatchEvent(new Event('change'));
        return row;
    };
    document.getElementById('add-attribute').addEventListener('click', () => {
        const row = appendAttribute({}); reindex();
        row.querySelector('.attribute-name').focus();
    });
    sheetButton.addEventListener('click', () => sheetInput.click());
    sheetInput.addEventListener('change', async () => {
        const file = sheetInput.files[0];
        if (!file) return;

        sheetButton.disabled = true;
        sheetButton.textContent = 'Importing...';
        sheetMessage.className = 'mb-4 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700';
        sheetMessage.textContent = 'Reading the attribute sheet...';

        try {
            const data = new FormData();
            data.append('attribute_sheet', file);
            const response = await fetch(sheetButton.dataset.importUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                body: data,
            });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || Object.values(payload.errors || {}).flat()[0] || 'The attribute sheet could not be imported.');

            const currentRows = [...list.querySelectorAll('.attribute-row')];
            if (currentRows.length === 1 && currentRows[0].querySelector('.attribute-name').value.trim() === '' && currentRows[0].querySelector('.attribute-value textarea').value.trim() === '') {
                currentRows[0].remove();
            }
            payload.attributes.forEach(appendAttribute);
            reindex();
            sheetMessage.className = 'mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700';
            sheetMessage.textContent = `${payload.attributes.length} attribute${payload.attributes.length === 1 ? '' : 's'} imported. Review them, then save the vendor.`;
        } catch (error) {
            sheetMessage.className = 'mb-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700';
            sheetMessage.textContent = error.message;
        } finally {
            sheetButton.disabled = false;
            sheetButton.textContent = 'Upload Attribute Sheet';
            sheetInput.value = '';
        }
    });
    document.getElementById('attribute-search').addEventListener('input', event => {
        const term = event.target.value.toLowerCase();
        [...list.querySelectorAll('.attribute-row')].forEach(row => row.classList.toggle('hidden', !row.querySelector('.attribute-name').value.toLowerCase().includes(term)));
    });
    document.querySelectorAll('[data-tab-button]').forEach(button => button.addEventListener('click', () => {
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.toggle('hidden', panel.dataset.tabPanel !== button.dataset.tabButton));
        document.querySelectorAll('.tab-button').forEach(item => { const active = item === button; item.classList.toggle('bg-[#3950a2]', active); item.classList.toggle('text-white', active); item.classList.toggle('text-slate-600', !active); });
    }));
    reindex();
});
</script>
