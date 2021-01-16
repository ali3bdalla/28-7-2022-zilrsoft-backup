@extends('accounting.layout.master')

@section('title')




@section("content")

    <shipping-method-transactions-table :shipping-method='@json($shipping)'>

    </shipping-method-transactions-table>
@endsection



@section("after_content")

@endsection