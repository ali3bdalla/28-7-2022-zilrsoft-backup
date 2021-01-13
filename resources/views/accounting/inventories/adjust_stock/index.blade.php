@extends('accounting.layout.master')

@section('title',__('sidebar.adjust_stock'))
@section('buttons')
    @can("manage inventory")
        <a href="{{route('inventory.adjustments.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> اضافة بيان
        </a>
    @endcan
@stop

@section("before_content")

@endsection



@section("content")

    <accounting-adjust-stock-datatable-component
            :is-deleted="1"
            :can-delete="{{auth()->user()->canDo("manage inventory")}}"
            :can-manage="{{auth()->user()->canDo("manage managers")}}"
    >


    </accounting-adjust-stock-datatable-component>
@endsection





@section("after_content")
@endsection