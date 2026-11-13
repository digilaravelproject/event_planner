@extends('admin.layout')

@section('content')
    @include('dynamic-vendors::partials.form', [
        'title' => 'Edit Vendor',
        'subtitle' => 'Update vendor information with a clear guided form.',
        'action' => route('admin.dynamic-vendors.update', $vendor),
        'method' => 'PUT',
    ])
@endsection
