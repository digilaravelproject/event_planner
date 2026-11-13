@extends('admin.layout')

@section('content')
    @include('dynamic-vendors::partials.form', [
        'title' => 'Add Vendor',
        'subtitle' => 'A simple guided form for every vendor category.',
        'action' => route('admin.dynamic-vendors.store'),
        'method' => 'POST',
        'vendor' => null,
    ])
@endsection
