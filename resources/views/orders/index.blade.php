@extends('accounting.layout.master')

@section('title',__('sidebar.orders'))




@section("content")
    <online-orders-table></online-orders-table>
@endsection



@section("after_content")
@endsection