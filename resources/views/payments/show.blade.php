@extends('layouts.master2')


@if($payment->payment_type=='receipt')
    @section('title',__('pages/payments.show_receipt'))
@else
    @section('title',__('pages/payments.show_payment'))
@endif
@section('desctipion',__('pages/payments.show'))
@section('route',route('management.payments.index'))


@section('translator')

@stop

@section('content')
    <div class="content">
        <div class="box">
            <div class="columns">
                <div class="column text-center">
                    <h5>{{__('pages/payments.amount')}} : {{ $payment->amount }} ريال</h5>
                </div>
                <div class="column text-center">
                    <h5> {{ $payment->amount_ar_words }} </h5>
                </div>
                <div class="column text-left">
                    <a target="_blank" href="{{ route('management.printers.voucher',$payment->id) }}" class="button
                    is-info"><i
                                class="fa fa-print"></i>
                        &nbsp;
                        &nbsp;
                        {{ __('reusable.print') }}</a>
                </div>
            </div>
        </div>


        <div class="box">
            <div class="columns">
                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__('pages/payments.type')}}
                                </span>
                            <input placeholder="{{__('pages/payments.type')}}" disabled
                                   value="@if($payment->paymentable_type=="App\Invoice"){{__('pages/payments.invoices_payment')
                                   }}@else{{__('pages/payments.advance_payment')}}@endif"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>


                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__
                                ('pages/payments.client')}}
                                </span>
                            <input placeholder="{{__('pages/payments.client')}}" disabled
                                   value="{{ $payment->user->name }}"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>

            </div>


            <div class="columns">
                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__
                                ('pages/payments.gateway')}}
                                </span>
                            <input placeholder="{{__('pages/payments.gateway')}}" disabled
                                   value="@if($payment->paymentable->parent_id>=1){{
                                   $payment->paymentable->parent->locale_name
                                   }} - @endif{{
                                   $payment->paymentable->locale_name
                                    }}"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>

                @if($payment->slug=='transfer' && $payment->payment_type=='payment')
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__
                                ('pages/payments.user_account')}}
                                </span>
                                <input placeholder="{{__('pages/payments.gateway')}}" disabled
                                       value="{{ $payment->user_gateway->locale_name }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                @endif

            </div>


            <div class="columns">
                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white">{{__
                                    ('reusable.creator')}}
                                    </span>
                            <input placeholder="{{__('reusable.creator')}}" disabled
                                   value="{{ $payment->creator->name }}"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>
                <div class="column">
                    <div class="filter_area">
                        <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white">{{__
                                    ('reusable.date')}}
                                    </span>
                            <input placeholder="{{__('reusable.date')}}" disabled
                                   value="{{ $payment->created_at }}"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>
            </div>

            @if($payment->description!='')
                <div class="row">
                    <div class="col-md-12">
                        <h5>{{__('pages/payments.payment_description')}}
                            : </h5>

                    </div>
                </div>


                <div class="columns">
                    <div class="column">
                        <textarea class="form-control" disabled>{{ $payment->description }}</textarea>
                    </div>
                </div>


            @endif


        </div>
    </div>
@stop
