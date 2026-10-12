@extends('emails.layouts.branded')
@section('eyebrow', 'SUBSCRIPTION UPDATE')
@section('heading', 'Your subscription has ended.')
@section('content')
<p style="margin-top:0">Hello {{ $subscription->user->name }},</p>
<p>Your <strong>{{ $subscription->plan->name }}</strong> subscription ended on {{ $subscription->ends_at?->format('d M Y') }}. Renew or select another plan to continue using subscription-only planning features.</p>
<p style="margin:26px 0"><a href="{{ route('user.subscription') }}" style="display:inline-block;background:#850625;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:bold">View subscription plans</a></p>
@endsection
