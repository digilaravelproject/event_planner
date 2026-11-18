@extends('admin.layout')

@section('content')
@php
    $editing = $category->exists;
    $formFields = old('fields', $category->fields ?? []);
@endphp
<div class="admin-page mx-auto max-w-6xl space-y-6">
    @include('admin.partials.alerts')

    <div>
        <a href="{{ route('admin.vendor-categories.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">← Vendor Categories</a>
        <h1 class="mt-2 text-3xl font-extrabold">{{ $editing ? 'Edit Vendor Category' : 'Add Vendor Category' }}</h1>
        <p class="mt-2 text-sm text-slate-500">These fields appear automatically after an administrator selects this category on the vendor form.</p>
    </div>

    @if($errors->any())<div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"><strong>Please correct the highlighted information.</strong><p class="mt-1">{{ $errors->first() }}</p></div>@endif

    <form method="POST" action="{{ $editing ? route('admin.vendor-categories.update', $category) : route('admin.vendor-categories.store') }}" class="space-y-6">
        @csrf
        @if($editing) @method('PUT') @endif

        <section class="admin-card p-6">
            <div class="grid gap-5 md:grid-cols-2">
                <label><span class="admin-form-label">Category name *</span><input required name="name" value="{{ old('name', $category->name) }}" class="admin-control w-full px-4 py-3" placeholder="Example: Photographer"></label>
                <label><span class="admin-form-label">URL slug</span><input name="slug" value="{{ old('slug', $category->slug) }}" class="admin-control w-full px-4 py-3" placeholder="Created automatically"></label>
                <label class="md:col-span-2"><span class="admin-form-label">Description</span><textarea name="description" rows="2" maxlength="500" class="admin-control w-full px-4 py-3" placeholder="Short description of this vendor service">{{ old('description', $category->description) }}</textarea></label>
                <label><span class="admin-form-label">Display order</span><input type="number" min="0" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}" class="admin-control w-full px-4 py-3"></label>
                <label class="flex items-center gap-3 self-end rounded-xl border border-orange-100 bg-orange-50/60 px-4 py-3"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active)) class="h-4 w-4 accent-orange-500"><span><strong class="block text-sm text-slate-800">Active category</strong><small class="text-slate-500">Available in the Add Vendor dropdown</small></span></label>
            </div>
        </section>

        <section class="admin-card overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-orange-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="text-lg font-extrabold text-slate-800">Category Form Fields</h2><p class="mt-1 text-xs text-slate-500">Configure the questions displayed when this category is selected.</p></div><button id="add-category-field" type="button" class="rounded-xl border border-orange-200 bg-orange-50 px-4 py-2.5 text-xs font-extrabold text-orange-600 hover:bg-orange-100">+ Add Field</button></div>
            <div id="category-fields" class="space-y-4 p-6">
                @foreach($formFields as $index => $field)
                    @include('dynamic-vendors::categories.partials.field', ['index' => $index, 'field' => $field, 'attributeTypes' => $attributeTypes])
                @endforeach
            </div>
            <p id="fields-empty" class="{{ count($formFields) ? 'hidden' : '' }} px-6 pb-6 text-center text-sm text-slate-500">No fields yet. Add the information administrators should enter for this vendor category.</p>
        </section>

        <div class="flex justify-end gap-3"><a href="{{ route('admin.vendor-categories.index') }}" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-600">Cancel</a><button class="admin-primary-button rounded-xl px-6 py-3 text-sm font-bold text-white">{{ $editing ? 'Save Category' : 'Create Category' }}</button></div>
    </form>
</div>

<template id="category-field-template">
    @include('dynamic-vendors::categories.partials.field', ['index' => '__INDEX__', 'field' => [], 'attributeTypes' => $attributeTypes])
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('category-fields');
    const template = document.getElementById('category-field-template');
    const empty = document.getElementById('fields-empty');
    const reindex = () => [...container.querySelectorAll('[data-category-field]')].forEach((row, index) => row.querySelectorAll('[name]').forEach(input => input.name = input.name.replace(/fields\[[^\]]+\]/, `fields[${index}]`)));
    const bind = () => container.querySelectorAll('[data-remove-field]').forEach(button => button.onclick = () => { button.closest('[data-category-field]').remove(); reindex(); empty.classList.toggle('hidden', container.children.length > 0); });
    document.getElementById('add-category-field').addEventListener('click', () => { container.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', container.children.length)); empty.classList.add('hidden'); bind(); container.lastElementChild.querySelector('input')?.focus(); });
    bind();
});
</script>
@endpush
