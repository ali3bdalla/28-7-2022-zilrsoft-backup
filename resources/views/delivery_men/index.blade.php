@extends('accounting.layout.master')

@section('title',"مندوبي التوصيل")

@section('buttons')
    <a href="{{route('delivery_men.create')}}" class="btn btn-custom-primary">
        <i class="fa fa-plus-circle"></i> اضافة
    </a>
@endsection



@section("content")
    <delivery-men-table></delivery-men-table>
@endsection



@section("after_content")
@endsection