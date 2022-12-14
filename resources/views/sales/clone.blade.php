@extends('accounting.layout.master')
@section('title',__('pages/invoice.create'))



@section('content')
    <accounting-sales-create-component
            :cloning='true'
            :quotation='@json($sale)'
            :can-create-item="{{ auth()->user()->canDo('create item') }}"
            :can-view-items="{{ auth()->user()->canDo('view item')  }}"
            :expenses='@json($expenses)'
            :is-order='@json($isOrder ?? $isOrder)'
            :clients='@json($clients)'
            :gateways='@json($gateways)'
            :salesmen='@json($salesmen)'
            :creator='@json($loggedManager)'
    ></accounting-sales-create-component>
@endsection