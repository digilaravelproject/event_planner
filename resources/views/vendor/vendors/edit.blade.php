@extends('vendor.layout')
@section('title', 'Edit Business Details') @section('page-title', 'Edit Business Details')
@section('content')
@include('dynamic-vendors::partials.form',['vendor'=>$vendor,'title'=>'Edit '.$vendor->name,'subtitle'=>'Update your details, services, attributes, media and SEO.','action'=>route('vendor.vendors.update',$vendor),'method'=>'PUT','routePrefix'=>'vendor.vendors'])
@endsection
