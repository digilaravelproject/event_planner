@extends('emails.layouts.branded')
@section('eyebrow', 'QUERY RESPONSE')
@section('heading', 'We have replied to your query.')
@section('content')
<p style="margin-top:0">Hello {{ $query->name }},</p>
<p>Our team has responded to <strong>{{ $query->subject }}</strong>.</p>
<div style="margin:18px 0;padding:16px;background:#faf7f2;border-radius:12px"><span style="font-size:11px;font-weight:bold;color:#94a3b8">YOUR MESSAGE</span><br>{{ $query->message }}</div>
<div style="margin:18px 0;padding:18px;border-left:4px solid #850625;background:#fff5f7;border-radius:0 12px 12px 0"><span style="font-size:11px;font-weight:bold;color:#850625">OUR REPLY</span><br>{{ $query->admin_reply }}</div>
<p><a href="{{ route('user.queries.index') }}" style="display:inline-block;background:#850625;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:bold">View your queries</a></p>
@endsection
