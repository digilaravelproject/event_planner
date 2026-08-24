<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserQueryReplyMail;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserQueryController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['search' => ['nullable', 'string', 'max:255'], 'status' => ['nullable', 'in:open,replied,closed']]);
        $queries = UserQuery::with('user')->when($filters['search'] ?? null, fn ($q, $s) => $q->where(fn ($q) => $q->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('subject', 'like', "%$s%")))->when($filters['status'] ?? null, fn ($q, $s) => $q->where('status', $s))->latest()->paginate(15)->withQueryString();
        return view('admin.user-queries.index', compact('queries', 'filters'));
    }

    public function update(Request $request, UserQuery $query)
    {
        $query->update($request->validate(['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255'], 'phone' => ['nullable', 'string', 'max:30'], 'subject' => ['required', 'string', 'max:255'], 'message' => ['required', 'string', 'max:5000'], 'status' => ['required', 'in:open,replied,closed']]));
        return back()->with('success', 'Query updated successfully.');
    }

    public function reply(Request $request, UserQuery $query)
    {
        $data = $request->validate(['admin_reply' => ['required', 'string', 'max:5000']]);
        $query->update(['admin_reply' => $data['admin_reply'], 'status' => 'replied', 'replied_at' => now()]);
        Mail::to($query->email)->send(new UserQueryReplyMail($query));
        return back()->with('success', 'Reply saved and emailed to the user.');
    }

    public function destroy(UserQuery $query) { $query->delete(); return back()->with('success', 'Query deleted successfully.'); }
}
