@extends('accounting.layout.master')


@section('title',__('pages/transactions.create'))



@section('page_css')
    <style>
        th {
            text-align: center !important;
        }

        .dropdown-menu {
            transform: translate3d(54px, 41px, 0px) !important;
        }
    </style>
@stop

@section('content')
    <accounting-transactions-create-component
            :accounts='@json($accounts)'
            :vendors='@json($vendors)'
            :clients='@json($clients)'
            :items='@json($items)'
    >

    </accounting-transactions-create-component>
    {{--<create-transaction-form-component :accounts='@json($accounts)'></create-transaction-form-component>--}}

@stop
