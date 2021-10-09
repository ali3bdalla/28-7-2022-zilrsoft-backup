@extends('accounting.layout.master')


@if($payment->payment_type=='receipt')
    @section('title',__('pages/vouchers.show_receipt'))
@else
    @section('title',__('pages/vouchers.show_payment'))
@endif


@section('buttons')
    <a target="_blank" href="{{ route('accounting.printer.voucher',$payment->id) }}" class="btn btn-custom-primary"><i
                class="fa
    fa-print"></i> {{ __('reusable.print') }}</a>
    @if($payment->isRefundable())
        <a target="_blank" href="{{ route('api.vouchers.refund',$payment->id) }}" class="btn btn-danger" onclick="return confirm('متاكد من الغاء السند')"><i
                    class="fa
        fa-redo"></i>  الغاء السند وعكس المبلغ</a>
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 text-center">
            <h5>{{__('pages/vouchers.amount')}} : <span
                        style="font-weight: bolder">{{ $payment->amount }} ريال</span></h5>
        </div>
        <div class="col-md-6 text-center">
            <h5> {{ $payment->amount_ar_words }} </h5>
        </div>

    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__('pages/vouchers.type')}}
                                </span>
                    <input placeholder="{{__('pages/vouchers.type')}}" disabled
                           value="@if($payment->account_type=="App\Models\Invoice"){{__('pages/vouchers.invoices_payment')
                                   }}@else{{__('pages/vouchers.advance_payment')}}@endif"
                           type="text" class="form-control">
                </div>

            </div>
        </div>


        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__
                                ('pages/vouchers.client')}}
                                </span>
                    <input placeholder="{{__('pages/vouchers.client')}}" disabled
                           value="{{ $payment->user->locale_name }}"
                           type="text" class="form-control">
                </div>

            </div>
        </div>

    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__
                                ('pages/vouchers.gateway')}}
                                </span>
                    <input placeholder="{{__('pages/vouchers.gateway')}}" disabled
                           value="{{
                                   $payment->account->locale_name
                                    }}"
                           type="text" class="form-control">

                    {{--                            @if($payment->account->parent_id>=1){{--}}
                    {{--                                   $payment->account->parent->locale_name--}}
                    {{--                                   }} - @endif--}}
                </div>

            </div>
        </div>

        @if($payment->slug=='transfer' && $payment->payment_type=='payment')
            <div class="col-md-6">
                <div class="filter_area">
                    <div class="input-group">
                                <span class="input-group-addon has-background-primary has-text-white">{{__
                                ('pages/vouchers.user_account')}}
                                </span>
                        <input placeholder="{{__('pages/vouchers.gateway')}}" disabled
                               value="{{ $payment->user_gateway->locale_name }}"
                               type="text" class="form-control">
                    </div>

                </div>
            </div>
        @endif

    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="filter_area">
                <div class="input-group">
                                    <span class="input-group-addon has-background-primary has-text-white">{{__
                                    ('reusable.creator')}}
                                    </span>
                    <input placeholder="{{__('reusable.creator')}}" disabled
                           value="{{ $payment->creator->locale_name }}"
                           type="text" class="form-control">
                </div>

            </div>
        </div>
        <div class="col-md-6">
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
                <h5>{{__('pages/vouchers.payment_description')}}
                    : </h5>

            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <label>
                    <textarea class="form-control" disabled>{{ $payment->description }}</textarea>
                </label>
            </div>
        </div>


    @endif

@stop
