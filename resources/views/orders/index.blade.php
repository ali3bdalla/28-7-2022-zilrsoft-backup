@extends('accounting.layout.master')

@section('title',__('sidebar.orders'))




@section("content")
    <accounting-orders-datatable-component></accounting-orders-datatable-component>
{{--    <online-orders-table></online-orders-table>--}}
@endsection



@section("after_content")
@endsection
