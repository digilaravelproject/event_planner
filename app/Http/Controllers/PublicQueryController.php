<?php

namespace App\Http\Controllers;

use App\Models\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicQueryController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['name' => ['required', 'string', 'max:255'], 'email' => ['required', 'email', 'max:255'], 'phone' => ['nullable', 'string', 'max:30'], 'subject' => ['required', 'string', 'max:255'], 'message' => ['required', 'string', 'max:5000']]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->withFragment('query-form');
        }
        $data = $validator->validated();
        UserQuery::create(array_merge($data, ['user_id' => $request->user()?->id]));
        return back()->with('query_success', 'Thank you. Your query has been submitted.')->withFragment('query-form');
    }
}
