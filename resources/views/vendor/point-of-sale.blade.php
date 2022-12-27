@extends('layouts.vendor-app')

@section('content')

    <point-of-sale prop-stores='@json($stores)'></point-of-sale>

@endsection
