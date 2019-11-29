@extends('layouts.master2')


@section('title',__('pages/transactions.create'))
@section('route',route('management.transactions.index'))



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
<create-transaction-form-component :accounts='@json($accounts)'></create-transaction-form-component>

@stop
