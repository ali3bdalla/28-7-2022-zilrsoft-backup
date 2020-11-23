@extends('accounting.layout.master')
@section('title',__('pages/invoice.purchase'))


@section('page_css')
    <style>
        .navbar {
            background-color: green !important;
        }
    </style>
@endsection
@section('content')
    <accounting-purchases-create-component
            :can-create-item="{{ auth()->user()->canDo('create item') }}"
            :can-view-items="{{ auth()->user()->canDo('view item')  }}"
            :expenses='@json($expenses)'
            :vendors='@json($vendors)'
            :gateways='@json($gateways)'
            :receivers='@json($receivers)'
            :creator='@json($loggedManager)'
    ></accounting-purchases-create-component>
@endsection