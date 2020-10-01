@extends('accounting.layout.master')
@section('title',__('sidebar.services_quotations'))

@section('content')
    <accounting-quotations-services-create-component
            :can-create-item="{{ auth()->user()->canDo('create item') }}"
            :can-view-items="{{ auth()->user()->canDo('view item')  }}"
            :services='@json($services)'
            :clients='@json($clients)'
            :gateways='@json($gateways)'
            :salesmen='@json($salesmen)'
            :creator='@json(auth()->user()->load('department','branch','user'))'
    >
    </accounting-quotations-services-create-component>
@endsection
