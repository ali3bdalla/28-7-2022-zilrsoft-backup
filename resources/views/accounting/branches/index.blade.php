@extends('accounting.layout.master')

@section('title',__('sidebar.branches'))
@section('buttons')
    @can("manage branches")
        <a href="{{route('accounting.branches.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/branches.create') }}
        </a>
    @endcan
@stop

@section("before_content")

@endsection



@section("content")


    <accounting-branches-datatable-component
            :can-edit="{{ auth()->user()->canDo('manage branches')}}"
            :can-create="{{ auth()->user()->canDo('manage branches')}}"
            :can-delete="{{ auth()->user()->canDo('manage branches')}}"
    >

    </accounting-branches-datatable-component>
@endsection





@section("after_content")
@endsection