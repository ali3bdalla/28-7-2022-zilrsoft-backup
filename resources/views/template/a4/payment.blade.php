@extends('layouts.pdfs.payment')



@section('content')

    <div class="container">
        <div class="organization">
            <div class="logo"><img src="{{asset(auth()->user()->organization->logo)}}"/> </div>
            <div class="organization_title">{{ config('head') }} </div>
        </div>

        <div class="header">{{ __('pages/payments.create_receipt') }}</div>
        <div class="payway">
            <div class="">
                {{ __('pages/payments.date') }}:  <strong>{{ $payment->created_at }}</strong>
            </div>

            <div class="">
                {{ __('pages/payments.number') }}:  <strong>{{ $payment->id * $payment->id - 1 }}</strong>
            </div>
        </div>

        <div class="username">
            {{ __('pages/payments.we_received') }}: <strong>{{ $payment->user->name }}</strong>
        </div>

        <div class="amount">
            {{ __('pages/payments.amount_total') }}: <strong> {{ $payment->amount }}</strong>
        </div>
        <div class="amount">
            {{  __('pages/payments.amount_total_in_words') }}: &nbsp;&nbsp;&nbsp;  <strong>{{ $payment->amount_ar_words
                }}</strong>
        </div>

        <div class="reason">
            {{ __('pages/payments.reason_for') }}: <strong>{{ __('pages/payments.reason_for_invoices') }}</strong>
        </div>


        <div class="payway">
            <div class="">
{{--                {{ __('pages/payments.received_on') }}: <strong>{{ $payment->organization_method->name }}</strong>--}}
            </div>
            <div class="">
{{--                {{ __('pages/payments.account') }}: <strong>{{$payment->organization_account->account--}}
                }}</strong>
            </div>
        </div>



        <div class="payway">
            <div class="">
{{--                {{ __('pages/payments.sended_from') }}: <strong>{{$payment->user_method->name}}</strong>--}}
            </div>
            <div class="">
{{--                {{ __('pages/payments.account') }}: <strong>{{$payment->user_account->account}}</strong>--}}
            </div>
        </div>










    </div>

@stop
