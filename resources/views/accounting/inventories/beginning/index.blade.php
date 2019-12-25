@extends('accounting.layout.master')

@section('title',__('sidebar.beginning_inventory'))
@section('buttons')
    @can("manage inventory")
        <a href="{{route('accounting.inventories.beginning.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.create') }}
        </a>
    @endcan
@stop

@section("before_content")

@endsection



@section("content")

    <accounting-beginning-datatable-component>


    </accounting-beginning-datatable-component>
@endsection





@section("after_content")
@endsection