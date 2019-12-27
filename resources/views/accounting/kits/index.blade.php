@extends('accounting.layout.master')

@section('title',__('sidebar.kits'))
@section('buttons')
    <a href="{{route('accounting.kits.create')}}" class="btn btn-custom-primary">
        <i class="fa fa-plus-circle"></i> {{ __('pages/items.create_kit') }}
    </a>
@stop

@section("before_content")

@endsection



@section("content")


    <accounting-kits-datatable-component
            :can-edit="{{ auth()->user()->canDo('manage kit')}}"
            :can-create="{{ auth()->user()->canDo('manage kit')}}"
            :categories='@json($categories)'
            :creators='@json($creators)'
            :can-view-accounting="{{ auth()->user()->canDo('view item transactions') }}"
            :can-delete="{{ auth()->user()->canDo('manage kit')}}">
    </accounting-kits-datatable-component>
@endsection





@section("after_content")
@endsection