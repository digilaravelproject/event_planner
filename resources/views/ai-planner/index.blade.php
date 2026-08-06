@extends('web.layouts.app')

@section('title', 'AI Event Planner - Shaadi Sense')

@section('content')
<div class="min-h-[70vh] py-16 px-4 bg-[#FAF7F2] flex items-center justify-center">
    <div class="max-w-3xl w-full bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-rose-100 text-center space-y-6">
        <div class="w-16 h-16 bg-[#850625]/10 text-[#850625] rounded-2xl flex items-center justify-center mx-auto text-2xl">
            <i class="fa-solid fa-wand-magic-sparkles"></i>
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold font-serif-luxury text-slate-900">
            AI Event Planner
        </h1>
        <p class="text-slate-600 text-base md:text-lg max-w-xl mx-auto font-sans-ui">
            Welcome to the AI Planner! Page set up successfully. We are ready to build the design and step-by-step interactive workflow.
        </p>
        <div class="pt-4">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-[#850625] hover:bg-[#6b041e] text-white px-6 py-3 rounded-full text-sm font-bold shadow-md transition-all">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Home</span>
            </a>
        </div>
    </div>
</div>
@endsection
