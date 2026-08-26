@extends('emails.layouts.branded')
@section('eyebrow', 'SUBSCRIPTION CONFIRMED')
@section('heading', 'You are ready to start planning.')
@section('content')
<p style="margin-top:0">Hello {{ $subscription->user->name }},</p>
<p>Your <strong>{{ $subscription->plan->name }}</strong> subscription is now active.</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:22px 0;background:#faf7f2;border-radius:14px"><tr><td style="padding:18px"><strong>Validity</strong><br>{{ $subscription->starts_at?->format('d M Y') }} to {{ $subscription->ends_at?->format('d M Y') }}<br><strong>Amount:</strong> {{ $subscription->currency }} {{ number_format($subscription->amount, 2) }}</td></tr></table>
<p><a href="{{ route('user.dashboard') }}" style="display:inline-block;background:#850625;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:bold">Open your dashboard</a></p>
@endsection
