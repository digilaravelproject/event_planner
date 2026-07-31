@extends('admin.layout')

@section('content')
    @include('dynamic-vendors::partials.form', [
        'title' => 'Edit Dynamic Vendor',
        'subtitle' => 'Every save creates a restorable version.',
        'action' => route('admin.dynamic-vendors.update', $vendor),
        'method' => 'PUT',
    ])
@endsection
