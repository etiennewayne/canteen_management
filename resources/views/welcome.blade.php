@extends('layouts.user')

@section('content')

    @auth
        <welcome-page prop-is-auth=1></welcome-page>
    @else
        <welcome-page prop-is-auth=0></welcome-page>
    @endauth
    

@endsection
