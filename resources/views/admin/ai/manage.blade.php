@extends('admin.layout')

@section('content')
<div class="admin-page max-w-5xl mx-auto">
    @include('admin.partials.module-header',['title'=>'AI Configuration','subtitle'=>'Securely configure the AI model and global instruction template.'])
    @include('admin.partials.alerts')
    @if($errors->any())<div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700">{{ $errors->first() }}</div>@endif
    <form action="{{ route('admin.ai.manage.save') }}" method="POST" class="admin-card p-6 sm:p-8 space-y-6">@csrf
        <div class="grid gap-6 md:grid-cols-2">
            <label class="block md:col-span-2"><span class="mb-2 block text-xs font-extrabold text-slate-700">OpenAI API Key</span><div class="relative"><input type="password" name="openai_api_key" id="openai_api_key" value="{{ old('openai_api_key',$apiKey) }}" autocomplete="off" class="admin-control w-full px-4 py-3 pr-20 font-mono text-sm"><button type="button" id="toggle-key" class="absolute right-2 top-2 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600">Show</button></div><span class="mt-1 block text-xs text-slate-400">Encrypted before database storage.</span></label>
            <label><span class="mb-2 block text-xs font-extrabold text-slate-700">Preferred AI Model</span><select name="openai_model" class="admin-control w-full px-4 py-3 text-sm">@foreach($models as $option)<option value="{{ $option->value }}" @selected(old('openai_model',$model)===$option->value)>{{ $option->label }}</option>@endforeach</select></label>
            <label class="md:col-span-2"><span class="mb-2 block text-xs font-extrabold text-slate-700">AI Prompt Template</span><textarea name="ai_prompt_template" rows="8" class="admin-control w-full px-4 py-3 text-sm" placeholder="Optional global instruction for AI requests">{{ old('ai_prompt_template',$promptTemplate) }}</textarea></label>
            <label><span class="mb-2 block text-xs font-extrabold text-slate-700">Status</span><select name="status" class="admin-control w-full px-4 py-3 text-sm"><option value="1" @selected(old('status',$status)==1)>Enabled</option><option value="0" @selected(old('status',$status)==0)>Disabled</option></select></label>
            <div class="rounded-xl bg-slate-50 p-4 text-xs text-slate-500"><p><strong class="text-slate-700">Last Updated:</strong> {{ $lastUpdated?->format('d M Y, h:i A') ?? 'Never' }}</p><p class="mt-2"><strong class="text-slate-700">Updated By:</strong> {{ $updatedBy }}</p></div>
        </div>
        <div class="flex justify-end gap-3 border-t border-slate-100 pt-5"><button type="reset" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600">Reset</button><button class="admin-primary-button rounded-xl px-6 py-2.5 text-sm font-bold text-white">Save Configuration</button></div>
    </form>
</div>
@endsection
@push('scripts')<script>document.getElementById('toggle-key')?.addEventListener('click',function(){const input=document.getElementById('openai_api_key');input.type=input.type==='password'?'text':'password';this.textContent=input.type==='password'?'Show':'Hide';});</script>@endpush
