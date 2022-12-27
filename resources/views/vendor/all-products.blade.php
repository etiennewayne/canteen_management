@extends('layouts.vendor-app')

@section('content')

    <all-products prop-stores='@json($stores)'></all-products>

@endsection
