@extends('accounting.layout.master')
@section('title',__('pages/invoice.create'))



@section('content')
{{--    <invoice-form--}}
{{--            :logged-manager='@json($loggedManager)'--}}
{{--    ></invoice-form>--}}
{{--    --}}
    <accounting-sales-create-component
            :can-create-item="{{ auth()->user()->canDo('create item') }}"
            :can-view-items="{{ auth()->user()->canDo('view item')  }}"
            :expenses='@json($expenses)'
            :clients='@json($clients)'
            :gateways='@json($gateways)'
            :salesmen='@json($salesmen)'

            :creator='@json($loggedManager)'
    ></accounting-sales-create-component>
@endsection
