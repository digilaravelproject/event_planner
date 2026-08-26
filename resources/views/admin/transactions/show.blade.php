@extends('admin.layout')

@section('content')
<div class="relative z-30 mt-6 space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-[#3950a2]">← Back to transactions</a><h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-800">Transaction #{{ $transaction->id }}</h1><p class="mt-1 text-sm font-semibold text-slate-500">Complete subscription payment and validity details.</p></div>
        <span class="inline-flex w-fit rounded-full px-4 py-2 text-xs font-extrabold uppercase {{ $transaction->status === 'active' ? 'bg-emerald-50 text-emerald-700' : ($transaction->status === 'failed' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">{{ $transaction->status }}</span>
    </div>
    <div class="grid gap-6 lg:grid-cols-2">
        @foreach([
            'Customer' => [$transaction->user?->name ?? 'Deleted user', $transaction->user?->email ?? 'N/A', $transaction->user?->mobile_number ?? 'N/A'],
            'Subscription' => [$transaction->plan?->name ?? 'Deleted plan', $transaction->plan?->durationLabel() ?? str($transaction->billing_cycle)->replace('_', ' ')->headline(), $transaction->currency.' '.number_format($transaction->amount, 2)],
            'Gateway references' => ['Order: '.($transaction->razorpay_order_id ?: 'N/A'), 'Payment: '.($transaction->razorpay_payment_id ?: 'N/A')],
            'Dates' => ['Starts: '.($transaction->starts_at?->format('d M Y, h:i A') ?? 'N/A'), 'Ends: '.($transaction->ends_at?->format('d M Y, h:i A') ?? 'N/A'), 'Paid: '.($transaction->paid_at?->format('d M Y, h:i A') ?? 'N/A'), 'Created: '.($transaction->created_at?->format('d M Y, h:i A') ?? 'N/A')],
        ] as $heading => $lines)
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400">{{ $heading }}</h2><div class="mt-4 space-y-2 text-sm text-slate-600">@foreach($lines as $line)<p class="break-all">{{ $line }}</p>@endforeach</div></section>
        @endforeach
    </div>
</div>
@endsection
