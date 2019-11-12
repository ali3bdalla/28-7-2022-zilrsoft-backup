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
                                   value="@if($payment->is_belongs_to_invoice==1){{__('pages/payments.invoices_payment')}}@else{{__('pages/payments.advance_payment')}}@endif"
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
                                   value="{{ $payment->gateway->name }}"
                                   type="text" class="form-control">
                        </div>

                    </div>
                </div>


                @if(in_array($payment->gateway_id,[2]))
            </div>
            @if($payment->payment_type=='receipt')
                <div class="columns">
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                            <span class="input-group-addon has-background-primary has-text-white">{{__
                                            ('pages/payments.user_bank'). ' '.__('pages/payments.transfer_from')}}
                                            </span>
                                <input placeholder="{{__
                                            ('pages/payments.user_bank'). ' '.__('pages/payments.transfer_from')}}"
                                       disabled
                                       value="{{ $payment->user_account->bank->name }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                            <span class="input-group-addon has-background-primary has-text-white">{{__
                                            ('pages/payments.account')}}
                                            </span>
                                <input placeholder="{{__('pages/payments.account')}}" disabled
                                       value="{{ $payment->user_account->account }}"
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
                                            ('pages/payments.organization_bank'). ' '.__('pages/payments.transfer_to')}}
                                            </span>
                                <input placeholder="{{__
                                            ('pages/payments.organization_bank'). ' '.__('pages/payments.transfer_to')}}"
                                       disabled
                                       value="{{ $payment->organization_account->bank->name }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                            <span class="input-group-addon has-background-primary has-text-white">{{__
                                            ('pages/payments.account')}}
                                            </span>
                                <input placeholder="{{__('pages/payments.account')}}" disabled
                                       value="{{ $payment->organization_account->account}}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>


            @else


                <div class="columns">
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                        <span class="input-group-addon has-background-primary has-text-white">{{__
                                        ('pages/payments.gateway')}}
                                        </span>
                                <input placeholder="{{__('pages/payments.gateway')}}" disabled
                                       value="{{ $payment->gateway->name }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                        <span class="input-group-addon has-background-primary has-text-white">{{__
                                        ('pages/payments.gateway')}}
                                        </span>
                                <input placeholder="{{__('pages/payments.gateway')}}" disabled
                                       value="{{ $payment->gateway->name }}"
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
                                       value="{{ $payment->gateway->name }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                        <span class="input-group-addon has-background-primary has-text-white">{{__
                                        ('pages/payments.gateway')}}
                                        </span>
                                <input placeholder="{{__('pages/payments.gateway')}}" disabled
                                       value="{{ $payment->gateway->name }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                </div>

            @endif

            <div class="columns">
                @endif

                @if(in_array($payment->gateway_id,[5]))
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white">{{__('pages/payments.bank')}}
                                    </span>
                                <input placeholder="{{__('pages/payments.bank')}}" disabled
                                       value="{{ $payment->bank->name }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>
            </div>
            <div class="columns">
                @endif

                @if(in_array($payment->gateway_id,[4,5]))
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__('pages/payments.reference_number')}}
                                </span>
                                <input placeholder="{{__('pages/payments.reference_number')}}" disabled
                                       value="{{ $payment->account }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>

                @endif

            </div>

            @if(in_array($payment->gateway_id,[6]))
                <div class="columns">
                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white">{{__
                                    ('pages/payments.email')}}
                                    </span>
                                <input placeholder="{{__('pages/payments.email')}}" disabled
                                       value="{{ $payment->account }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="column">
                        <div class="filter_area">
                            <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white">{{__
                                    ('pages/payments.reference_number')}}
                                    </span>
                                <input placeholder="{{__('pages/payments.reference_number')}}" disabled
                                       value="{{ $payment->account_name }}"
                                       type="text" class="form-control">
                            </div>

                        </div>
                    </div>

                </div>
            @endif


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


        </div>
    </div>
@stop
