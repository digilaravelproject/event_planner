@extends('admin.layout')
@section('content')
@php($meta = old('meta', $item->meta ?? []))
<div class="admin-page mx-auto max-w-4xl">
    @include('admin.partials.module-header', ['title' => ($item->exists ? 'Edit ' : 'Add ').$label, 'subtitle' => 'This content is published directly on the landing page.'])
    @if($errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>@endif
    <form method="POST" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.landing-content.update',[$type,$item]) : route('admin.landing-content.store',$type) }}" class="admin-card space-y-6 p-6 sm:p-8">@csrf @if($item->exists)@method('PUT')@endif
        <div class="grid gap-5 md:grid-cols-2">
            <label class="md:col-span-2"><span class="mb-2 block text-xs font-bold">Title *</span><input name="title" required value="{{ old('title',$item->title) }}" class="admin-control w-full px-4 py-3"></label>
            <label class="md:col-span-2"><span class="mb-2 block text-xs font-bold">Subtitle / Context</span><input name="subtitle" value="{{ old('subtitle',$item->subtitle) }}" class="admin-control w-full px-4 py-3"></label>
            <label class="md:col-span-2"><span class="mb-2 block text-xs font-bold">Description</span><textarea name="body" rows="4" class="admin-control w-full px-4 py-3">{{ old('body',$item->body) }}</textarea></label>
            @if($type === 'how-it-works')
                <label><span class="mb-2 block text-xs font-bold">Time / Eyebrow</span><input name="eyebrow" value="{{ old('eyebrow',$meta['eyebrow'] ?? '') }}" placeholder="Two minutes" class="admin-control w-full px-4 py-3"></label>
                <label><span class="mb-2 block text-xs font-bold">Footer label</span><input name="footer" value="{{ old('footer',$meta['footer'] ?? '') }}" placeholder="Guided Input" class="admin-control w-full px-4 py-3"></label>
            @elseif($type === 'comparisons')
                <label><span class="mb-2 block text-xs font-bold">Comparison side *</span><select name="side" class="admin-control w-full px-4 py-3"><option value="manual" @selected(old('side',$meta['side'] ?? '')==='manual')>Old Manual Way</option><option value="ai" @selected(old('side',$meta['side'] ?? '')==='ai')>Smart AI Planning</option></select></label>
                <label><span class="mb-2 block text-xs font-bold">Badge / Result</span><input name="footer" value="{{ old('footer',$meta['footer'] ?? '') }}" class="admin-control w-full px-4 py-3"></label>
            @else
                <label><span class="mb-2 block text-xs font-bold">Rating</span><input type="number" min="1" max="5" name="rating" value="{{ old('rating',$meta['rating'] ?? 5) }}" class="admin-control w-full px-4 py-3"></label>
                <label><span class="mb-2 block text-xs font-bold">Date</span><input name="date" value="{{ old('date',$meta['date'] ?? '') }}" placeholder="Nov 2025" class="admin-control w-full px-4 py-3"></label>
                <label><span class="mb-2 block text-xs font-bold">Result badge</span><input name="footer" value="{{ old('footer',$meta['footer'] ?? '') }}" placeholder="₹ 2.4 Lakh Saved" class="admin-control w-full px-4 py-3"></label>
                <label><span class="mb-2 block text-xs font-bold">Photo</span><input type="file" name="image" accept="image/*" class="admin-control w-full px-4 py-2.5">@if($item->image)<input type="hidden" name="existing_image" value="{{ $item->image }}"><img src="{{ str_starts_with($item->image,'http') ? $item->image : asset('storage/'.$item->image) }}" class="mt-3 h-20 w-20 rounded-xl object-cover" alt="">@endif</label>
            @endif
            <label><span class="mb-2 block text-xs font-bold">Display order *</span><input type="number" min="0" name="display_order" value="{{ old('display_order',$item->display_order ?? 0) }}" class="admin-control w-full px-4 py-3"></label>
            <label class="flex items-end gap-2 pb-3 text-sm font-bold"><input type="checkbox" name="status" value="1" @checked(old('status',$item->status ?? true))> Active</label>
        </div>
        <div class="flex justify-end gap-3 border-t pt-5"><a href="{{ route('admin.landing-content.index',$type) }}" class="rounded-xl border px-5 py-2.5 text-sm font-bold">Cancel</a><button class="admin-primary-button rounded-xl px-6 py-2.5 text-sm font-bold text-white">Save Content</button></div>
    </form>
</div>
@endsection
