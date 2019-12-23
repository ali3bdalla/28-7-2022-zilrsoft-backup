@extends('layouts.master2')


@section('title',__('pages/payments.create_payment'))
@section('desctipion','')
@section('route',route('management.payments.index'))



@section('translator')
    <script defer>
        window.translator = `@json(trans('pages/payments'))`
    </script>
@stop



@section('content')


    <create-voucher-form-component
            payment_type="payment"
            :accounts='@json($accounts)'
            :voucher_types='@json($voucher_types)'
            :users='@json($users)'
    >

    </create-voucher-form-component>
    {{--    <create-payment-form-component--}}
    {{--            :accounts='@json($accounts)'--}}
    {{--            :users='@json($users)'--}}

    {{--    ></create-payment-form-component>--}}


@stop
