@extends('layouts.print-layout')

@section('content')

    <product-adjustment prop-stores='@json($stores)'></product-adjustment>

@endsection
