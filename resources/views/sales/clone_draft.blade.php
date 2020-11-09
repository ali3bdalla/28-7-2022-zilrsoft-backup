@extends('accounting.layout.master')
@section('title',__('pages/invoice.create'))



@section('content')
{{--    <accounting-quotations-create-component--}}
{{--            :can-create-item="{{ auth()->user()->canDo('create item') }}"--}}
{{--            :can-view-items="{{ auth()->user()->canDo('view item')  }}"--}}
{{--            :expenses='@json($expenses)'--}}
{{--            :clients='@json($clients)'--}}
{{--            :gateways='@json($gateways)'--}}
{{--            :salesmen='@json($salesmen)'--}}
{{--            :creator='@json(auth()->user()->load('department','branch','user'))'--}}
{{--    ></accounting-quotations-create-component>--}}


    <accounting-quotations-create-component
            :cloning='true'
            :quotation='@json($sale)'
            :can-create-item="{{ auth()->user()->canDo('create item') }}"
            :can-view-items="{{ auth()->user()->canDo('view item')  }}"
            :expenses='@json($expenses)'
            :clients='@json($clients)'
            :gateways='@json($gateways)'
            :salesmen='@json($salesmen)'
            :creator='@json(auth()->user()->load('department','branch','user'))'
    ></accounting-quotations-create-component>
@endsection