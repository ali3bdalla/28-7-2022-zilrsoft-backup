@extends('accounting.layout.master')

@section('title',__('sidebar.items'))
@section('buttons')
    @can("create item")
        <a href="{{route('items.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/items.create') }}
        </a>
    @endcan
@stop

@section("before_content")

@endsection



@section("content")


    <accounting-items-datatable-component
            :can-edit="{{ auth()->user()->canDo('edit item')}}"
            :can-create="{{ auth()->user()->canDo('create item')}}"
            :categories='@json($categories)'
            :creators='@json($creators)'
            :can-view-accounting="{{ auth()->user()->canDo('view item transactions') }}"
            :can-delete="{{ auth()->user()->canDo('delete item')}}">


    </accounting-items-datatable-component>
@endsection





@section("after_content")
@endsection