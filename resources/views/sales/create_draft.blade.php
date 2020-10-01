@extends('accounting.layout.master')
@section('title',__('pages/invoice.create'))


@section('page_css')
    <style>
        .navbar {
            background-color: #b8b83a !important;
        }
    </style>
@endsection

@section('content')
    <accounting-quotations-create-component
            :can-create-item="{{ auth()->user()->canDo('create item') }}"
            :can-view-items="{{ auth()->user()->canDo('view item')  }}"
            :expenses='@json($expenses)'
            :clients='@json($clients)'
            :gateways='@json($gateways)'
            :salesmen='@json($salesmen)'
            :creator='@json(auth()->user()->load('department','branch','user'))'
    ></accounting-quotations-create-component>
@endsection