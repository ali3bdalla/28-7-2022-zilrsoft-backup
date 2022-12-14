@extends('accounting.layout.master')

@section('title',"تسوية المخزون")
@section('buttons')
{{--    @can("manage inventory")--}}
{{--        <a href="{{route('accounting.inventories.adjust_stock.create')}}" class="btn btn-custom-primary">--}}
{{--            <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.create') }}--}}
{{--        </a>--}}
{{--    @endcan--}}
@stop

@section("before_content")

@endsection



@section("content")

    <accounting-adjust-stock-datatable-component
            :is-deleted="0"
            :can-delete="{{auth()->user()->canDo("manage inventory")}}"
            :can-manage="{{auth()->user()->canDo("manage managers")}}"
    >


    </accounting-adjust-stock-datatable-component>
@endsection





@section("after_content")
@endsection