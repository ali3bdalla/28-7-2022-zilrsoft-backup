@extends('accounting.layout.master')

@section('title',__('sidebar.purchases'))
@section('buttons')
    @can("create purchase")
        <a href="{{route('accounting.purchases.create')}}" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.create') }}
        </a>
    @endcan
@stop

@section("before_content")

@endsection


@section('page_css')
    <style>
        .navbar {
            background-color: green !important;
        }
    </style>
@endsection
@section("content")

    <accounting-purchases-datatable-component
            :creators='@json($creators)'
            :vendors='@json($vendors)'
            :is-pending='{{$is_pending ? 1 : 0}}'
            :departments='@json(\App\Department::all())'
            :can-edit="{{ auth()->user()->canDo('edit purchase') }}"
            :can-confirm="{{ auth()->user()->canDo('confirm purchase') }}"
    >


    </accounting-purchases-datatable-component>
@endsection





@section("after_content")
@endsection