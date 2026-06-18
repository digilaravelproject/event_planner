@extends('admin.layout')

@section('content')
<div class="space-y-6 mt-16 relative z-30">
    <!-- Header Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight font-serif-display">User Management</h1>
            <p class="text-sm text-white/80 mt-1 font-medium">Manage user accounts, view active subscriptions, and moderate login statuses.</p>
            @include('admin.partials.alerts')
        </div>
    </div>

    <!-- Users Table -->
    <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm text-slate-700">
                <thead>
                    <tr class="bg-slate-50/75 text-left font-bold text-slate-500">
                        <th class="px-6 py-4.5">User Details</th>
                        <th class="px-6 py-4.5">Mobile Number</th>
                        <th class="px-6 py-4.5">Subscription Plan</th>
                        <th class="px-6 py-4.5">Ends At</th>
                        <th class="px-6 py-4.5 text-center">Status</th>
                        <th class="px-6 py-4.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition duration-150">
                            <!-- User Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm uppercase">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Phone -->
                            <td class="px-6 py-4 font-medium text-slate-600">
                                {{ $user->mobile_number ?? 'N/A' }}
                            </td>

                            <!-- Plan -->
                            <td class="px-6 py-4">
                                @if($user->subscription)
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-xs font-bold text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        {{ $user->subscription->name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-md bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600">
                                        Free Tier / None
                                    </span>
                                @endif
                            </td>

                            <!-- Subscription Expiry -->
                            <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                                @if($user->subscription_ends_at)
                                    {{ $user->subscription_ends_at->format('M d, Y H:i') }}
                                @else
                                    N/A
                                @endif
                            </td>

                            <!-- Status Toggle -->
                            <td class="px-6 py-4 text-center">
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-1 focus:ring-blue-500 {{ $user->status ? 'bg-emerald-500' : 'bg-slate-200' }}" role="switch">
                                        <span class="sr-only">Toggle Status</span>
                                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $user->status ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </form>
                            </td>

                            @php
                                $userData = [
                                    'name' => $user->name,
                                    'email' => $user->email,
                                    'mobile_number' => $user->mobile_number ?? 'N/A',
                                    'status' => $user->status ? 'Active' : 'Inactive',
                                    'plan' => $user->subscription?->name ?? 'Free Tier / None',
                                    'ends_at' => $user->subscription_ends_at?->format('M d, Y H:i') ?? 'N/A',
                                    'registered' => $user->created_at?->format('M d, Y H:i') ?? 'N/A',
                                ];
                            @endphp

                            <!-- Actions -->
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3.5">
                                    <!-- View Modal Toggle -->
                                    <button type="button" 
                                            onclick='openUserModal(@json($userData))'
                                            class="text-slate-400 hover:text-slate-600 transition" 
                                            title="View Details">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.43 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </button>

                                    <!-- Edit Link -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-400 hover:text-indigo-600 transition" title="Edit User">
                                        <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 21.75a4.5 4.5 0 01-2.013 1.24l-3.113.882a.375.375 0 01-.485-.486l.883-3.113a4.5 4.5 0 011.24-2.013L17.285 4.487zm0 0L19.5 6.72" />
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action is irreversible.')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600 transition focus:outline-none" title="Delete User">
                                            <svg class="h-5.5 w-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 font-medium">No users registered yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div id="user-details-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-500/75 backdrop-blur-sm transition-opacity" onclick="closeUserModal()"></div>

        <!-- Modal panel container -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-100">
            <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900" id="modal-title">User Account Details</h3>
                    <button type="button" onclick="closeUserModal()" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="mt-4 space-y-4 text-sm text-slate-600">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400 font-semibold">Full Name:</span>
                        <span id="modal-user-name" class="col-span-2 font-bold text-slate-800"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400 font-semibold">Email:</span>
                        <span id="modal-user-email" class="col-span-2 font-bold text-slate-800 break-all"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400 font-semibold">Mobile:</span>
                        <span id="modal-user-mobile" class="col-span-2 font-bold text-slate-800"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400 font-semibold">Status:</span>
                        <div>
                            <span id="modal-user-status" class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold"></span>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400 font-semibold">Current Plan:</span>
                        <span id="modal-user-plan" class="col-span-2 font-bold text-slate-800"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400 font-semibold">Expires At:</span>
                        <span id="modal-user-ends" class="col-span-2 font-bold text-slate-800"></span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-slate-400 font-semibold">Registered:</span>
                        <span id="modal-user-registered" class="col-span-2 font-bold text-slate-850"></span>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 px-6 py-4 flex justify-end">
                <button type="button" onclick="closeUserModal()" class="rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold px-4.5 py-2.5 transition">
                    Close Details
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openUserModal(user) {
        document.getElementById('modal-user-name').innerText = user.name;
        document.getElementById('modal-user-email').innerText = user.email;
        document.getElementById('modal-user-mobile').innerText = user.mobile_number;
        
        const statusEl = document.getElementById('modal-user-status');
        statusEl.innerText = user.status;
        if (user.status === 'Active') {
            statusEl.className = 'inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20';
        } else {
            statusEl.className = 'inline-flex items-center rounded-full bg-rose-50 px-2.5 py-0.5 text-xs font-bold text-rose-700 ring-1 ring-inset ring-rose-600/20';
        }

        document.getElementById('modal-user-plan').innerText = user.plan;
        document.getElementById('modal-user-ends').innerText = user.ends_at;
        document.getElementById('modal-user-registered').innerText = user.registered;

        document.getElementById('user-details-modal').classList.remove('hidden');
    }

    function closeUserModal() {
        document.getElementById('user-details-modal').classList.add('hidden');
    }
</script>
@endsection
