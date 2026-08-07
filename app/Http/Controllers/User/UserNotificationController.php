<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserNotificationController extends Controller
{
    public function index(Request $request): View
    {
        $notifications = $request->user()->adminNotifications()->visibleToUsers()
            ->orderByDesc('notifications.sent_at')->orderByDesc('notifications.created_at')->paginate(15);

        return view('user.notifications.index', compact('notifications'));
    }

    public function read(Request $request, AdminNotification $notification): RedirectResponse
    {
        abort_unless($notification->visibleToUsers()->whereKey($notification->id)->exists(), 404);
        abort_unless($request->user()->adminNotifications()->whereKey($notification->id)->exists(), 403);

        $request->user()->adminNotifications()->updateExistingPivot($notification->id, [
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function readAll(Request $request): RedirectResponse
    {
        $ids = $request->user()->adminNotifications()->visibleToUsers()->wherePivot('is_read', false)->pluck('notifications.id');
        foreach ($ids as $id) {
            $request->user()->adminNotifications()->updateExistingPivot($id, ['is_read' => true, 'read_at' => now()]);
        }

        return back()->with('success', 'All notifications marked as read.');
    }
}
