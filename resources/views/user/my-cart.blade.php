@extends('layouts.user')
@section('content')
    <my-cart prop-role='{{$role}}' prop-offices='@json($offices)'></my-cart>
@endsection
