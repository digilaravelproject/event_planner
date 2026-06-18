@extends('admin.layout')

@section('content')
<div class="max-w-xl mx-auto space-y-6 mt-6 relative z-30">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manage AI Credentials</h1>
        <p class="text-sm text-slate-500 mt-1 font-semibold">Configure OpenAI API keys and parameters to integrate AI capabilities into the frontend planner.</p>
        @include('admin.partials.alerts')
    </div>

    <!-- Form Panel -->
    <div class="rounded-xl bg-white p-6 sm:p-8 shadow-sm border border-slate-200/60">
        <form action="{{ route('admin.ai.manage.save') }}" method="POST" class="space-y-5">
            @csrf

            <!-- OpenAI API Key -->
            <div>
                <label for="openai_api_key" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">OpenAI API Key</label>
                <input type="password" name="openai_api_key" id="openai_api_key" value="{{ old('openai_api_key', $apiKey) }}" placeholder="sk-proj-..."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all font-mono">
                <p class="text-[10px] text-slate-400 mt-1.5 font-semibold">Your API key is stored securely in the database settings matrix.</p>
                @error('openai_api_key') <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Model Selection -->
            <div>
                <label for="openai_model" class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Preferred AI Model</label>
                <select name="openai_model" id="openai_model" required
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#3950a2]/20 focus:border-[#3950a2] transition-all">
                    <option value="gpt-4o" {{ old('openai_model', $model) == 'gpt-4o' ? 'selected' : '' }}>gpt-4o (Recommended - Faster & Smarter)</option>
                    <option value="gpt-4o-mini" {{ old('openai_model', $model) == 'gpt-4o-mini' ? 'selected' : '' }}>gpt-4o-mini (Lightweight & Cost-effective)</option>
                    <option value="gpt-4" {{ old('openai_model', $model) == 'gpt-4' ? 'selected' : '' }}>gpt-4 (Standard legacy model)</option>
                    <option value="o1-mini" {{ old('openai_model', $model) == 'o1-mini' ? 'selected' : '' }}>o1-mini (Reasoning focused model)</option>
                </select>
                @error('openai_model') <p class="text-xs text-red-500 mt-1.5 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Action buttons -->
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6 mt-6">
                <a href="{{ route('admin.dashboard') }}" class="rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-semibold px-5 py-2.5 transition">
                    Back to Dashboard
                </a>
                <button type="submit" class="rounded-xl bg-[#3950a2] hover:bg-[#2c3e80] text-white text-sm font-semibold px-6 py-2.5 transition shadow-sm hover:shadow cursor-pointer active:scale-[0.99]">
                    Save AI Configuration
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
