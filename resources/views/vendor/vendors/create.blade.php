@extends('vendor.layout')
@section('title', 'Add Business Details') @section('page-title', 'Add Business Details')
@section('content')
@include('dynamic-vendors::partials.form',['vendor'=>null,'title'=>'Add Business / Vendor Details','subtitle'=>'Describe your services using flexible, ordered attributes.','action'=>route('vendor.vendors.store'),'method'=>'POST','routePrefix'=>'vendor.vendors'])
@endsection
