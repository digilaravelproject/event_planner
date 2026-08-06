@extends('admin.layout')

@section('content')
<div class="admin-page max-w-5xl mx-auto">
    @include('admin.partials.module-header',['title'=>'OpenRouter Configuration','subtitle'=>'Securely configure OpenRouter, select any synchronized text model, and manage the event-planning prompt.'])
    @include('admin.partials.alerts')
    @if($errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>@endif
    <form action="{{ route('admin.ai.manage.save') }}" method="POST" class="admin-card p-6 sm:p-8 space-y-6">@csrf
        <div class="grid gap-6 md:grid-cols-2">
            <label class="block md:col-span-2"><span class="mb-2 block text-xs font-extrabold text-slate-700">OpenRouter API Key</span><div class="relative"><input type="password" name="openrouter_api_key" id="openrouter_api_key" value="" placeholder="{{ $hasApiKey ? 'Key configured — enter a new key only to replace it' : 'sk-or-v1-…' }}" autocomplete="new-password" class="admin-control w-full px-4 py-3 pr-20 font-mono text-sm"><button type="button" id="toggle-key" class="absolute right-2 top-2 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600">Show</button></div><span class="mt-1 block text-xs text-slate-400">Encrypted in the database. The saved key is never displayed again.</span></label>
            <label class="md:col-span-2"><span class="mb-2 block text-xs font-extrabold text-slate-700">OpenRouter Model</span><select name="openrouter_model" class="admin-control w-full px-4 py-3 text-sm">@foreach($models as $option)<option value="{{ $option->value }}" @selected(old('openrouter_model',$model)===$option->value)>{{ $option->label }}</option>@endforeach</select>@if($modelLoadError)<span class="mt-2 block text-xs text-amber-600">{{ $modelLoadError }}</span>@else<span class="mt-2 block text-xs text-slate-400">{{ $models->count() }} text-generation models available.</span>@endif</label>
            <label class="md:col-span-2"><span class="mb-2 block text-xs font-extrabold text-slate-700">Event Planner System Prompt</span><textarea name="openrouter_prompt_template" rows="10" class="admin-control w-full px-4 py-3 text-sm">{{ old('openrouter_prompt_template',$promptTemplate) }}</textarea></label>
            <label><span class="mb-2 block text-xs font-extrabold text-slate-700">Status</span><select name="status" class="admin-control w-full px-4 py-3 text-sm"><option value="1" @selected(old('status',$status)==1)>Enabled</option><option value="0" @selected(old('status',$status)==0)>Disabled</option></select></label>
            <div class="rounded-xl bg-slate-50 p-4 text-xs text-slate-500"><p><strong class="text-slate-700">Last Updated:</strong> {{ $lastUpdated?->format('d M Y, h:i A') ?? 'Never' }}</p><p class="mt-2"><strong class="text-slate-700">Updated By:</strong> {{ $updatedBy }}</p></div>
        </div>
        <div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><button type="reset" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600">Reset</button><button class="admin-primary-button rounded-xl px-6 py-2.5 text-sm font-bold text-white">Save Configuration</button></div>
    </form>
</div>
@endsection
@push('scripts')<script>document.getElementById('toggle-key')?.addEventListener('click',function(){const input=document.getElementById('openrouter_api_key');input.type=input.type==='password'?'text':'password';this.textContent=input.type==='password'?'Show':'Hide';});</script>@endpush
