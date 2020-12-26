@extends('accounting.layout.master')

@section('title',$branch->locale_name . ' | ' .__('pages/branches.departments'))
@section('buttons')
    @can("manage branches")
        <a href="{{route('accounting.branches.departments.create',$branch->id)}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/branches.create_department') }}
        </a>
    @endcan
@stop

@section("before_content")

@endsection



@section("content")


    <accounting-departments-datatable-component
            :branch='@json($branch)'
            :can-edit="{{ auth()->user()->canDo('manage branches')}}"
            :can-create="{{ auth()->user()->canDo('manage branches')}}"
            :can-delete="{{ auth()->user()->canDo('manage branches')}}"
    >

    </accounting-departments-datatable-component>
@endsection





@section("after_content")
@endsection