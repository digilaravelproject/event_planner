@extends('admin.layout')

@section('content')
<div class="admin-page mx-auto max-w-5xl">
    @include('admin.partials.module-header', [
        'title' => $page->exists ? 'Edit Page' : 'Create Page',
        'subtitle' => 'Write formatted page content and control its availability.',
    ])
    @if($errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>@endif

    <form method="POST" action="{{ $page->exists ? route('admin.pages.update', $page) : route('admin.pages.store') }}" id="page-form" class="admin-card space-y-5 p-6 sm:p-8">
        @csrf
        @if($page->exists) @method('PUT') @endif
        <div class="grid gap-5 md:grid-cols-2">
            <label><span class="mb-2 block text-xs font-bold">Title *</span><input name="title" id="page-title" required value="{{ old('title', $page->title) }}" class="admin-control w-full px-4 py-3"></label>
            <label><span class="mb-2 block text-xs font-bold">Slug *</span><input name="slug" id="page-slug" required value="{{ old('slug', $page->slug) }}" placeholder="generated-from-title" class="admin-control w-full px-4 py-3 font-mono"><span class="mt-1 block text-xs text-slate-400">Used as the page's stable URL name.</span></label>
        </div>

        <div>
            <span class="mb-2 block text-xs font-bold">Description *</span>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white focus-within:border-[#3950a2] focus-within:ring-2 focus-within:ring-[#3950a2]/10">
                <div id="editor-toolbar" class="flex flex-wrap gap-1 border-b border-slate-200 bg-slate-50 p-2">
                    @foreach([['bold','Bold','B'],['italic','Italic','I'],['underline','Underline','U'],['formatBlock','Heading','H2'],['insertUnorderedList','Bullet list','• List'],['insertOrderedList','Numbered list','1. List'],['createLink','Add link','Link'],['removeFormat','Clear formatting','Clear']] as [$command,$title,$label])
                        <button type="button" data-command="{{ $command }}" title="{{ $title }}" class="rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-indigo-50 hover:text-indigo-700">{{ $label }}</button>
                    @endforeach
                </div>
                <div id="page-editor" contenteditable="true" class="min-h-72 px-4 py-3 text-sm leading-7 text-slate-700 outline-none" data-placeholder="Write the page description...">{!! old('description', $page->description) !!}</div>
            </div>
            <textarea name="description" id="page-description" class="hidden">{{ old('description', $page->description) }}</textarea>
            <p class="mt-2 text-xs text-slate-400">Formatting is sanitized when saved. Scripts, event handlers and unsafe links are removed.</p>
        </div>

        <label class="flex items-center gap-2 text-sm font-bold"><input type="checkbox" name="status" value="1" @checked(old('status', $page->status ?? true))> Active</label>
        <div class="flex justify-end gap-3"><a href="{{ route('admin.pages.index') }}" class="rounded-xl border px-5 py-2.5 text-sm font-bold">Cancel</a><button class="admin-primary-button rounded-xl px-6 py-2.5 text-sm font-bold text-white">Save Page</button></div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('page-form');
    const editor = document.getElementById('page-editor');
    const description = document.getElementById('page-description');
    const title = document.getElementById('page-title');
    const slug = document.getElementById('page-slug');
    let slugEdited = slug.value.trim() !== '';

    document.querySelectorAll('#editor-toolbar [data-command]').forEach(button => button.addEventListener('click', () => {
        const command = button.dataset.command;
        let value = null;
        if (command === 'formatBlock') value = 'h2';
        if (command === 'createLink') {
            value = prompt('Enter a safe URL (https://, mailto:, tel:, /path or #anchor):');
            if (!value) return;
        }
        editor.focus();
        document.execCommand(command, false, value);
    }));
    slug.addEventListener('input', () => slugEdited = slug.value.trim() !== '');
    title.addEventListener('input', () => {
        if (!slugEdited) slug.value = title.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    });
    form.addEventListener('submit', () => description.value = editor.innerHTML);
});
</script>
@endpush
