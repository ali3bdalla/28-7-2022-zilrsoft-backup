@extends('accounting.layout.master')

@section('title',__('sidebar.filters'))
@section('buttons')
    @can("create filter")
        <a href="{{route('accounting.filters.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/filters.create') }}
        </a>
    @endcan
@stop

@section("before_content")

@endsection



@section("content")


    <accounting-filters-datatable-component
            :can-edit="{{ auth()->user()->canDo('edit filter')}}"
            :can-create="{{ auth()->user()->canDo('create filter')}}"
            :can-delete="{{ auth()->user()->canDo('delete filter')}}">


    </accounting-filters-datatable-component>
@endsection





@section("after_content")
@endsection