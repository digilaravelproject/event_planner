@extends('admin.layout')

@section('content')
<div class="admin-page mx-auto max-w-5xl">
    @include('admin.partials.module-header', ['title' => $page->title, 'subtitle' => '/'.$page->slug, 'actionUrl' => route('admin.pages.edit', $page), 'actionLabel' => 'Edit Page'])
    @include('admin.partials.alerts')
    <div class="admin-card p-6 sm:p-8">
        <div class="mb-6 flex items-center justify-between border-b border-slate-100 pb-4"><span class="font-mono text-xs text-slate-400">/{{ $page->slug }}</span>@include('admin.partials.status-badge', ['status' => $page->status])</div>
        <article class="space-y-4 text-sm leading-7 text-slate-700 [&_a]:text-indigo-600 [&_a]:underline [&_blockquote]:border-l-4 [&_blockquote]:border-indigo-200 [&_blockquote]:pl-4 [&_h2]:text-xl [&_h2]:font-extrabold [&_h3]:text-lg [&_h3]:font-bold [&_ol]:list-decimal [&_ol]:pl-6 [&_ul]:list-disc [&_ul]:pl-6">{!! $page->description !!}</article>
    </div>
</div>
@endsection
