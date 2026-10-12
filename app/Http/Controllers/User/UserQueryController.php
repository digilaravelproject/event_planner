<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\AdminNewQueryMail;
use App\Models\UserQuery;
use App\Support\EmailRecipients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserQueryController extends Controller
{
    public function index(Request $request)
    {
        return view('user.queries.index', ['queries' => $request->user()->queries()->latest()->paginate(10)]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->withFragment('query-form');
        } $query = $request->user()->queries()->create(array_merge($validator->validated(), ['name' => $request->user()->name, 'email' => $request->user()->email]));
        Mail::to(EmailRecipients::ADMIN)->send(new AdminNewQueryMail($query));

        return back()->with('success', 'Your query has been submitted.')->withFragment('query-form');
    }

    public function update(Request $request, UserQuery $query)
    {
        $this->own($request, $query);
        abort_if($query->replied_at, 422, 'A replied query cannot be edited.');
        $query->update($this->validated($request));

        return back()->with('success', 'Query updated successfully.');
    }

    public function destroy(Request $request, UserQuery $query)
    {
        $this->own($request, $query);
        $query->delete();

        return back()->with('success', 'Query deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate($this->rules());
    }

    private function rules(): array
    {
        return ['phone' => ['nullable', 'string', 'max:30'], 'subject' => ['required', 'string', 'max:255'], 'message' => ['required', 'string', 'max:5000']];
    }

    private function own(Request $request, UserQuery $query): void
    {
        abort_unless($query->user_id === $request->user()->id, 404);
    }
}
