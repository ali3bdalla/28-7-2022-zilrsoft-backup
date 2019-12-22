@extends('accounting.layout.master')

@section('title',__('pages/users.managers'))
@section('buttons')
    @can("manage managers")
        <a href="{{route('accounting.managers.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/users.create_manager') }}
        </a>
    @endcan
@stop


@section("content")
    <accounting-managers-datatable-component
            :can-edit="{{ auth()->user()->canDo('manage managers')}}"
            :can-create="{{ auth()->user()->canDo('manage managers')}}"
            :branches='@json($branches)'
            :can-delete="{{ auth()->user()->canDo('manage managers')}}">


    </accounting-managers-datatable-component>
@endsection