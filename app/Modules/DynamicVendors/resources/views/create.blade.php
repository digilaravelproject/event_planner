@extends('admin.layout')

@section('content')
    @include('dynamic-vendors::partials.form', [
        'title' => 'Add Dynamic Vendor',
        'subtitle' => 'Define any vendor using ordered, typed attributes.',
        'action' => route('admin.dynamic-vendors.store'),
        'method' => 'POST',
        'vendor' => null,
    ])
@endsection
