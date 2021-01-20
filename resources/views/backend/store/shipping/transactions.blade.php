@extends('accounting.layout.master')

@section('title')





@section('buttons')

        <a  href="{{route('store.shipping.create_transaction',$shipping->id)}}"  class="btn btn-default">
             انشاء بوليصة
        </a>
@stop



@section("content")

    
    <shipping-method-transactions-table :shipping-method='@json($shipping)'>

    </shipping-method-transactions-table>
@endsection



@section("after_content")

@endsection