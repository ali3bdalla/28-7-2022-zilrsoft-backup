@extends('accounting.layout.master')

@section('title',__('sidebar.users'))
@section('buttons')
    @can("create identity")
        <a href="{{route('accounting.identities.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/users.create') }}
        </a>
    @endcan
@stop

@section("before_content")

@endsection



@section("content")


    <accounting-identities-datatable-component
            :can-edit="{{ auth()->user()->canDo('view identity')}}"
            :can-create="{{ auth()->user()->canDo('create identity')}}"
            :can-delete="{{ auth()->user()->canDo('view identity')}}">


    </accounting-identities-datatable-component>
@endsection





@section("after_content")
@endsection