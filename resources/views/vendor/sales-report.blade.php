@extends('layouts.print-layout')

@section('content')

<sales-report prop-stores='@json($stores)'></sales-report>

@endsection
