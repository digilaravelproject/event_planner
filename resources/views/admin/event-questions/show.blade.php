@extends('admin.layout')

@section('content')
<div class="admin-page mx-auto max-w-5xl space-y-6">
    @include('admin.partials.module-header', [
        'title' => 'Question Overview',
        'subtitle' => 'Review the question, answer options, mapping, and selected images.',
    ])

    <section class="admin-card p-6 sm:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Question</p>
                <h1 class="mt-2 text-2xl font-extrabold text-slate-900">{{ $question->question }}</h1>
                <p class="mt-2 font-mono text-xs text-slate-400">{{ $question->question_code }}</p>
            </div>
            @include('admin.partials.status-badge', ['status' => $question->status])
        </div>

        <dl class="mt-7 grid gap-4 border-y border-slate-100 py-5 sm:grid-cols-2 lg:grid-cols-4">
            <div><dt class="text-xs font-bold text-slate-400">Type</dt><dd class="mt-1 text-sm font-extrabold capitalize text-slate-800">{{ str($question->question_type)->replace('_', ' ') }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-400">Display order</dt><dd class="mt-1 text-sm font-extrabold text-slate-800">{{ $question->display_order }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-400">Required</dt><dd class="mt-1 text-sm font-extrabold text-slate-800">{{ $question->is_required ? 'Yes' : 'No' }}</dd></div>
            <div><dt class="text-xs font-bold text-slate-400">Vendor attribute</dt><dd class="mt-1 text-sm font-extrabold text-slate-800">{{ $question->vendor_attribute_label ?: 'Not mapped' }}</dd></div>
        </dl>

        <div class="mt-7">
            <h2 class="text-base font-extrabold text-slate-900">Answer options</h2>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                @forelse($question->options ?? [] as $index => $option)
                    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-xs font-extrabold text-indigo-700">{{ $index + 1 }}</span>
                        <span class="break-words text-sm font-semibold text-slate-700">{{ $option }}</span>
                    </div>
                @empty
                    <p class="text-sm text-slate-400">This question does not use predefined options.</p>
                @endforelse
            </div>
        </div>

        @if(count($question->vendor_attribute_images ?? []))
            <div class="mt-7">
                <h2 class="text-base font-extrabold text-slate-900">Selected option images</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($question->vendor_attribute_images as $image)
                        <a href="{{ asset('storage/'.$image) }}" target="_blank" rel="noopener" class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50 p-2">
                            <img src="{{ asset('storage/'.$image) }}" alt="Selected image for {{ $question->question }}" class="h-40 w-full rounded-lg object-cover">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-8 flex justify-end gap-3 border-t border-slate-100 pt-6">
            <a href="{{ route('admin.event-questions.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-700">Back</a>
            <a href="{{ route('admin.event-questions.edit', $question) }}" class="admin-primary-button rounded-xl px-5 py-2.5 text-sm font-bold text-white">Edit Question</a>
        </div>
    </section>
</div>
@endsection
