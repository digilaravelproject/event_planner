@extends('emails.layouts.branded')
@section('eyebrow', 'NEW VENDOR REGISTRATION')
@section('heading', 'A new vendor joined Shaadi Sense.')
@section('content')
<p style="margin-top:0"><strong>{{ $vendor->business_name }}</strong> has created a vendor account.</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin:22px 0;background:#faf7f2;border-radius:14px"><tr><td style="padding:18px"><strong>Contact:</strong> {{ $vendor->name }}<br><strong>Email:</strong> {{ $vendor->email }}<br><strong>Phone:</strong> {{ $vendor->phone }}<br><strong>Category:</strong> {{ $vendor->category ?: 'Not provided' }}<br><strong>City:</strong> {{ $vendor->city ?: 'Not provided' }}</td></tr></table>
<p><a href="{{ route('admin.vendor-analytics.index') }}" style="display:inline-block;background:#3950a2;color:#fff;text-decoration:none;padding:14px 24px;border-radius:12px;font-weight:bold">Open vendor administration</a></p>
@endsection
