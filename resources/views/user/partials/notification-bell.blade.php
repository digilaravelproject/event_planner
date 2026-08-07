@php
    $userNotifications = $userNotifications ?? collect();
    $userUnreadNotificationCount = $userUnreadNotificationCount ?? 0;
@endphp
<div class="relative" x-data="{ notificationsOpen: false }" @click.outside="notificationsOpen = false">
    <button type="button" @click="notificationsOpen = !notificationsOpen" :aria-expanded="notificationsOpen" aria-label="Open notifications"
        class="relative flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:border-rose-200 hover:text-[#850625]">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
        @if($userUnreadNotificationCount > 0)
            <span class="absolute -right-1 -top-1 flex min-h-5 min-w-5 items-center justify-center rounded-full bg-[#850625] px-1 text-[9px] font-extrabold text-white">{{ $userUnreadNotificationCount > 99 ? '99+' : $userUnreadNotificationCount }}</span>
        @endif
    </button>
    <div x-show="notificationsOpen" x-transition class="absolute right-0 z-[70] mt-3 w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-rose-100 bg-white shadow-2xl" style="display:none">
        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
            <div><p class="text-sm font-extrabold text-slate-900">Notifications</p><p class="text-[10px] text-slate-400">{{ $userUnreadNotificationCount }} unread</p></div>
            @if($userUnreadNotificationCount > 0)
                <form method="POST" action="{{ route('user.notifications.read-all') }}">@csrf @method('PATCH')<button class="text-[10px] font-bold text-[#850625]">Mark all read</button></form>
            @endif
        </div>
        <div class="max-h-96 overflow-y-auto">
            @forelse($userNotifications as $notification)
                <form method="POST" action="{{ route('user.notifications.read', $notification) }}" class="border-b border-slate-100 last:border-0">@csrf @method('PATCH')
                    <button class="block w-full px-4 py-3 text-left transition hover:bg-rose-50/60 {{ $notification->pivot->is_read ? 'bg-white' : 'bg-amber-50/55' }}">
                        <span class="flex items-start justify-between gap-3"><span class="text-xs font-extrabold text-slate-800">{{ $notification->title }}</span><span class="rounded-full px-2 py-0.5 text-[9px] font-bold {{ $notification->pivot->is_read ? 'bg-slate-100 text-slate-500' : 'bg-[#850625] text-white' }}">{{ $notification->pivot->is_read ? 'Read' : 'Unread' }}</span></span>
                        <span class="mt-1 block line-clamp-2 text-[11px] leading-5 text-slate-500">{{ $notification->message }}</span>
                        <span class="mt-1 block text-[9px] font-semibold text-slate-400">{{ ($notification->sent_at ?? $notification->schedule_at ?? $notification->created_at)->diffForHumans() }}</span>
                    </button>
                </form>
            @empty
                <div class="px-5 py-10 text-center text-xs text-slate-400">No notifications yet.</div>
            @endforelse
        </div>
        <a href="{{ route('user.notifications.index') }}" class="block border-t border-slate-100 px-4 py-3 text-center text-xs font-extrabold text-[#850625] hover:bg-rose-50">View all notifications</a>
    </div>
</div>
