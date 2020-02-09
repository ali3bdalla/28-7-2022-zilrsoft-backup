@extends('accounting.layout.master')

@section('title',__('sidebar.quotations'))
@section('buttons')
    <a href="{{route('accounting.quotations.create')}}" class="btn btn-custom-primary">
        <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.create') }}
    </a>


{{--    <a href="{{route('accounting.quotations.create')}}" class="btn btn-custom-primary">--}}
{{--        <i class="fa fa-plus-circle"></i> {{ __('pages/invoice.service_invoice') }}--}}
{{--    </a>--}}

@stop

@section('page_css')
    <style>
        .navbar {
            background-color: #b8b83a !important;
        }
    </style>
@endsection

@section("content")

    <accounting-sales-datatable-component
            :only-quotations="true"
            :creators='@json($creators)'
            :vendors='@json($clients)'
            :creator='@json(auth()->user())'
    >


    </accounting-sales-datatable-component>
@endsection
