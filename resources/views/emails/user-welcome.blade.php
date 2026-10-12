@extends('emails.layouts.branded')
@section('eyebrow', 'WELCOME ABOARD')
@section('heading', 'Your celebration planning starts here.')
@section('content')
<p style="margin-top:0">Hello {{ $user->name }},</p>
<p>Your Shaadi Sense account has been created successfully. Choose the subscription that fits your plans, then build your event step by step with budgets, vendors, and recommendations in one place.</p>
<p style="margin:26px 0"><a href="{{ route('user.subscription') }}" style="display:inline-block;background:#850625;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:bold">Choose a subscription</a></p>
@endsection
