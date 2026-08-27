@extends('emails.layouts.branded')
@section('eyebrow', 'SUBSCRIPTION REMINDER')
@section('heading', 'Your subscription ends in one week.')
@section('content')
<p style="margin-top:0">Hello {{ $subscription->user->name }},</p>
<p>Your <strong>{{ $subscription->plan->name }}</strong> subscription is scheduled to end on {{ $subscription->ends_at?->format('d M Y') }}. Renew now to keep using subscription-only planning features without interruption.</p>
<p style="margin:26px 0"><a href="{{ route('user.subscription') }}" style="display:inline-block;background:#850625;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:bold">View subscription plans</a></p>
@endsection
