@extends('admin.layout')

@section('content')
<div class="mt-6 space-y-6 relative z-30">
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-800">Payment Transactions</h1>
            <p class="mt-1 text-sm font-semibold text-slate-500">Review subscription payments, free activations, gateway references, and validity dates.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.transactions.export.pdf', request()->query()) }}" class="rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-rose-700">Download PDF</a>
            <a href="{{ route('admin.transactions.export.excel', request()->query()) }}" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-emerald-700">Download Excel</a>
        </div>
    </div>

    <form method="GET" class="flex flex-col md:flex-row gap-3 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <input name="search" value="{{ request('search') }}" placeholder="User, email, phone, plan or gateway ID" class="flex-1 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#3950a2] focus:outline-none">
        <select name="status" class="w-full md:w-56 rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-600 focus:border-[#3950a2] focus:outline-none">
            <option value="">All statuses</option>
            @foreach(['created', 'active', 'failed', 'expired'] as $status)
                <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="w-full md:w-auto rounded-xl bg-[#3950a2] px-6 py-3 text-sm font-bold text-white hover:bg-[#2c3e80]">Filter</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-xs">
                <thead class="bg-slate-50 uppercase tracking-wider text-slate-500">
                    <tr><th class="p-4">User</th><th class="p-4">Plan</th><th class="p-4">Payment</th><th class="p-4">Gateway IDs</th><th class="p-4">Validity</th><th class="p-4">Status</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($transactions as $transaction)
                        <tr class="align-top hover:bg-slate-50/70">
                            <td class="p-4"><div class="font-bold text-slate-800">{{ $transaction->user?->name ?? 'Deleted user' }}</div><div class="mt-1 text-slate-500">{{ $transaction->user?->email ?? 'N/A' }}</div><div class="text-slate-400">{{ $transaction->user?->mobile_number ?? 'N/A' }} · User #{{ $transaction->user_id }}</div></td>
                            <td class="p-4"><div class="font-bold text-slate-800">{{ $transaction->plan?->name ?? 'Deleted plan' }}</div><div class="mt-1 text-slate-500">{{ $transaction->plan?->durationLabel() ?? str($transaction->billing_cycle)->replace('_', ' ')->headline() }}</div></td>
                            <td class="p-4"><div class="font-bold text-slate-800">{{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}</div><div class="mt-1 text-slate-400">Paid: {{ $transaction->paid_at?->format('d M Y, h:i A') ?? 'N/A' }}</div><div class="text-slate-400">Created: {{ $transaction->created_at?->format('d M Y, h:i A') }}</div></td>
                            <td class="max-w-[230px] p-4 text-slate-500"><div class="break-all"><span class="font-bold text-slate-700">Order:</span> {{ $transaction->razorpay_order_id ?: 'N/A' }}</div><div class="mt-1 break-all"><span class="font-bold text-slate-700">Payment:</span> {{ $transaction->razorpay_payment_id ?: 'N/A' }}</div></td>
                            <td class="p-4 text-slate-500"><div>{{ $transaction->starts_at?->format('d M Y') ?? 'N/A' }}</div><div class="my-1 text-slate-300">to</div><div>{{ $transaction->ends_at?->format('d M Y') ?? 'N/A' }}</div></td>
                            <td class="p-4"><span class="inline-flex rounded-full px-3 py-1 font-bold uppercase {{ $transaction->status === 'active' ? 'bg-emerald-50 text-emerald-700' : ($transaction->status === 'failed' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">{{ $transaction->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="p-12 text-center font-semibold text-slate-400">No payment transactions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())<div class="border-t border-slate-100 p-4">{{ $transactions->links() }}</div>@endif
    </div>
</div>
@endsection
