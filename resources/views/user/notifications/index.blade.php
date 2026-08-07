@extends('user.layout')
@section('title', 'Notifications - Shaadi Sense')
@section('content')
<div class="mx-auto max-w-4xl">
    <div class="mb-6 flex items-end justify-between gap-4">
        <div><p class="text-[10px] font-extrabold uppercase tracking-[.2em] text-[#850625]">Your updates</p><h1 class="serif-title mt-1 text-4xl text-slate-900">Notifications</h1></div>
        @if($notifications->contains(fn($notification) => ! $notification->pivot->is_read))
            <form method="POST" action="{{ route('user.notifications.read-all') }}">@csrf @method('PATCH')<button class="rounded-xl border border-rose-200 bg-white px-4 py-2 text-xs font-bold text-[#850625]">Mark all as read</button></form>
        @endif
    </div>
    <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
        @forelse($notifications as $notification)
            <div class="border-b border-slate-100 p-5 last:border-0 {{ $notification->pivot->is_read ? '' : 'bg-amber-50/40' }}">
                <div class="flex items-start justify-between gap-4"><div><div class="flex items-center gap-2"><h2 class="text-sm font-extrabold text-slate-900">{{ $notification->title }}</h2><span class="rounded-full px-2 py-1 text-[9px] font-bold {{ $notification->pivot->is_read ? 'bg-slate-100 text-slate-500' : 'bg-[#850625] text-white' }}">{{ $notification->pivot->is_read ? 'Read' : 'Unread' }}</span></div><p class="mt-2 text-xs leading-6 text-slate-600">{{ $notification->message }}</p><p class="mt-2 text-[10px] text-slate-400">{{ ($notification->sent_at ?? $notification->schedule_at ?? $notification->created_at)->format('d M Y, h:i A') }}</p></div>
                    @unless($notification->pivot->is_read)<form method="POST" action="{{ route('user.notifications.read', $notification) }}">@csrf @method('PATCH')<button class="whitespace-nowrap rounded-xl bg-rose-50 px-3 py-2 text-[10px] font-bold text-[#850625]">Mark read</button></form>@endunless
                </div>
            </div>
        @empty
            <div class="p-16 text-center text-sm text-slate-400">You do not have any notifications yet.</div>
        @endforelse
    </div>
    <div class="mt-5">{{ $notifications->links() }}</div>
</div>
@endsection
