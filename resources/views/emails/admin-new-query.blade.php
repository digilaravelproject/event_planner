@extends('emails.layouts.branded')
@section('eyebrow', 'ADMIN NOTIFICATION')
@section('heading', 'A new user query needs attention.')
@section('content')
<p style="margin-top:0"><strong>{{ $query->name }}</strong> ({{ $query->email }}) submitted a new query.</p>
<div style="margin:22px 0;padding:18px;border-left:4px solid #850625;background:#faf7f2;border-radius:0 12px 12px 0"><strong>{{ $query->subject }}</strong><br>{{ $query->message }}</div>
<p><a href="{{ route('admin.user-queries.index') }}" style="display:inline-block;background:#3950a2;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:bold">Review user queries</a></p>
@endsection
