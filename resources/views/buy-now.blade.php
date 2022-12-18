@extends('layouts.user')

@section('content')
    <buy-now prop-product-id='{{ $product_id }}'
        prop-product='@json($product)'
        prop-role='{{ $role }}'></buy-now>
@endsection