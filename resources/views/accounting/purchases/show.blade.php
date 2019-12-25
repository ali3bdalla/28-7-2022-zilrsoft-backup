@extends('accounting.layout.master')


@section('title',__('pages/invoice.purchase'))
@section('desctipion',__('pages/invoice.view_purchase'))
@section('route',route('accounting.purchases.index'))


@section('content')
    <div class="message">


        <div class="message-header has-background-dark">
            <h4 class="title has-text-white pull-right">{{ __('pages/invoice.invoice_number') }} {{$purchase->prefix . $purchase->invoice_id}}</h4>
            <div class="columns">
                <div class="column">
                    <print-invoice-component :invoice_id="{{$purchase->invoice_id}}"
                                             :title='@json( __('pages/invoice.price_invoice'))'></print-invoice-component>
                    @if($purchase->invoice_type=='purchase')
                        {{--                        <a href="{{ route('accounting.purchases.clone',$purchase->id) }}" class="button is-primary"><i--}}
                        {{--                                    class="fa fa-copy"></i>&nbsp; {{ __('pages/invoice.clone')--}}
                        {{--                        }}</a>&nbsp;--}}
                        <a href="{{ route('accounting.purchases.edit',$purchase->id) }}" class="button is-warning"><i
                                    class="fa
                        fa-redo"></i>&nbsp; {{ __('pages/invoice.return')
                        }}</a>&nbsp;
                    @endif

                </div>
            </div>

        </div>
        <div class="message-body">
            <div class="has-background-light">
                <div class="columns">
                    <div class="column">
                        <!-- <label>client name</label> -->
                        <div class="">
                            <div class="input-group">
                                <span class="input-group-addon"
                                      id="vendors-list">{{ __('pages/invoice.vendor') }}</span>

                                <input type="text" name="" aria-describedby="time-field" class="form-control"
                                       disabled="" value="{{$purchase->vendor->name}}">

                            </div>


                        </div>
                    </div>
                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon"
                                  id="vendor-in-number">{{ __('pages/invoice.vendor_inc_number') }}</span>
                            <input type="text" name="" aria-describedby="vendor-in-number" class="form-control"
                                   disabled="" value="{{$purchase->vendor_inc_number}}">

                        </div>


                    </div>

                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon" id="time-field">{{ __('pages/invoice.date') }}</span>
                            <input type="text" name="" aria-describedby="time-field" style="    direction: ltr
                            !important;" class="form-control" disabled=""
                                   value="{{$purchase->created_at}}">

                        </div>


                    </div>


                </div>


                <div>
                    <div class="columns">
                        <div class="column">
                            <!-- <label>client name</label> -->
                            <div class="">
                                <div class="input-group">
                                    <span class="input-group-addon" id="receivers-list">{{ __('pages/invoice.receiver')
                                    }}</span>
                                    <input type="text" name="" aria-describedby="receivers-list" class="form-control"
                                           disabled="" value="{{$purchase->receiver->name}}">

                                </div>

                            </div>
                        </div>

                        <div class="column">
                            <div class="input-group">
                                <span class="input-group-addon" id="department-field">{{ __('pages/invoice.department')
                                }}</span>
                                <input type="text" name="" aria-describedby="department-field" readonly
                                       class="form-control" disabled=""
                                       value="{{$purchase->invoice->branch->name}}">

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <hr>
            <div class="table-container">
                <table class="table is-bordered text-center is-fullwidth is-narrow is-hoverable is-striped">
                    <thead class="has-background-dark ">
                    <tr>
                        <th class="has-text-white">{{ __('pages/invoice.id') }}</th>
                        <th class="has-text-white">{{ __('pages/invoice.barcode') }}</th>
                        <th class="has-text-white" width="20%">{{ __('pages/invoice.item_name') }}</th>
                        <th class="has-text-white" width="3%">{{ __('pages/invoice.qty') }}</th>
                        <th class="has-text-white">{{ __('pages/invoice.price') }}</th>
                        <th class="has-text-white">{{ __('pages/invoice.total') }}</th>
                        <th class="has-text-white">{{ __('pages/invoice.discount') }}</th>
                        <th class="has-text-white">{{ __('pages/invoice.subtotal') }}</th>
                        <th class="has-text-white">{{ __('pages/invoice.vat') }}</th>
                        <th class="has-text-white">{{ __('pages/invoice.tax') }}</th>
                        <th class="has-text-white">{{ __('pages/invoice.net') }}</th>
                    </tr>
                    </thead>

                    <tbody>


                    @foreach($purchase->invoice->items as $item)
                        @if($item->item->is_expense || $purchase->invoice->parent_invoice_id<=0)
                            <tr>
                                <th class="has-text-white">
                                    <button class="button is-info is-small">{{$loop->index + 1}}</button>
                                </th>
                                <!-- <th class="has-text-white"></th> -->
                                <th text="item.barcode">{{ $item->item->barcode }}</th>
                                <th text="item.name">{{ $item->item->locale_name }}</th>
                                <th width="6%">
                                    <input type="text" class="input" value="{{ $item->qty }}" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" value="{{ $item->price }}" disabled="">

                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" value="{{ $item->total }}" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="discount"
                                           value="{{ $item->discount }}"
                                           disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="subtotal" readonly="" value="{{
                                $item->subtotal }}" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="vat purchase" readonly="" value="{{
                                $item->item->vtp }}%" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="tax" readonly="" value="{{ $item->tax
                                }}" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="net" readonly="" value="{{ $item->net
                                }}" disabled="">
                                </th>

                            </tr>
                        @endif
                    @endforeach


                    </tbody>
                </table>
            </div>
            <div class="form-group">
                <div class="columns">


                    <div data-v-73cd913d="" class="column is-three-quarters">
                        <div data-v-73cd913d="">


                            <div class="panel panel-primary">

                                <div class="panel-body">
                                    <div class="panel-heading">
                                        تكاليف اضافية
                                    </div>
                                    <table class="table table-bordered">
                                        @foreach($purchase->invoice->expenses as $expense)
                                            <tr>

                                                <!-- <th class="has-text-white"></th> -->

                                                <th text="item.name"
                                                    style="text-align: right !important;">{{
                                                    $expense->expense->locale_name }} - @if($expense->with_net)
                                                        <span class="badge badge-primary">مضمنة</span>


                                                    @else
                                                        <span class="badge badge-info">مستقلة</span>
                                                    @endif
                                                </th>

                                                <th class="has-text-white">
                                                    <input type="text" class="input" value="{{ $expense->amount }}"
                                                           disabled="">

                                                </th>


                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>


                            <div class="panel panel-primary">
                                <div class="panel-body">
                                    @if(!empty($purchase->invoice->payments))
                                        @foreach($purchase->invoice->payments as $payment)
                                            <div class="form-group">
                                                <div class="input-group"><span id="1" class="input-group-addon"
                                                                               style="min-width: 130px; font-weight: bolder;
">{{$payment->paymentable->locale_name}} &nbsp;&nbsp; ( <a target="_blank" href="{{ route('accounting.payments.show',
$payment->id)
}}">عرض السند</a> )</span>
                                                    <input aria-describedby="1" disabled="disabled" type="text"
                                                           class="form-control" value="{{$payment->amount}}" style="font-weight:
                                                   bolder;">
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>


                                <div class="padding:30px">
                                    <div class="columns text-center ">
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">الاجمالي</span>
                                                    <h1 class="title text-center">{{$purchase->invoice->net}}</h1></div>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">المدفوع</span>
                                                    <h1 class="title text-center">{{money_format("%i",
                                                $purchase->invoice->net
                                                                                      - $purchase->invoice->remaining)}}</h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">المتبقي / آجل</span>
                                                    <h1 class="title text-center"><input disabled="disabled"
                                                                                         value="{{ $purchase->invoice->remaining}}"
                                                                                         readonly="readonly" type="text"
                                                                                         class="form-control is-danger has-error onlyhidden"
                                                                                         style="font-size: 32px; height: 36px;">
                                                        <input readonly="readonly" disabled="disabled" type="text"
                                                               class="form-control is-danger has-error onlyhidden"
                                                               style="font-size: 32px; height: 36px; display: none;">
                                                    </h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-footer">
                                    <table class="table table-bordered table-hover text-center">
                                        <thead>
                                        <th>التاريخ</th>
                                        <th>اسم الحساب</th>
                                        <th>مدين</th>
                                        <th>دائن</th>
                                        </thead>
                                        <tbody class="text-center">
								<?php $total_debit = 0; $total_credit = 0;?>
                                        @foreach($transactions as $transaction)
                                            @if($purchase->invoice_type=='r_purchase')
                                                @if($transaction['description']=='to_item' ||
                                            $transaction['description']=='to_tax')
										   <?php $total_credit = $total_credit + $transaction['amount']?>

                                                     <tr>
                                                         <td class="datedirection">{{ $transaction->created_at }}</td>
                                                         <td>{{ $transaction->creditable->locale_name }}</td>
                                                         <td></td>
                                                         <td>{{money_format("%i", $transaction->amount) }}</td>
                                                     </tr>
                                                @else

										   <?php $total_debit = $total_debit + $transaction['amount']?>
                                                     <tr>
                                                         <td class="datedirection">{{ $transaction->created_at }}</td>
                                                         <td>{{ $transaction->debitable->locale_name }}</td>
                                                         <td>{{money_format("%i", $transaction->amount) }}</td>
                                                         <td></td>
                                                     </tr>
                                                @endif




                                            @else



                                                @if($transaction['description']=='to_item' || $transaction['description']=='to_tax')

										   <?php $total_debit = $total_debit + $transaction['amount']?>
                                                     <tr>
                                                         <td class="datedirection">{{ $transaction->created_at }}</td>
                                                         <td>{{ $transaction->debitable->locale_name }}</td>
                                                         <td>{{money_format("%i", $transaction->amount) }}</td>
                                                         <td></td>
                                                     </tr>

                                                @else


										   <?php $total_credit = $total_credit + $transaction['amount']?>

                                                     <tr>
                                                         <td class="datedirection">{{ $transaction->created_at }}</td>
                                                         <td>{{ $transaction->creditable->locale_name }}</td>
                                                         <td></td>
                                                         <td>{{money_format("%i", $transaction->amount) }}</td>
                                                     </tr>
                                                @endif

                                            @endif
                                        @endforeach
                                        </tbody>
                                        <thead>
                                        <th>المجموع</th>
                                        <th></th>
                                        <th>{{ money_format("%i",$total_debit) }}</th>
                                        <th>{{ money_format("%i",$total_credit) }}</th>
                                        </thead>
                                    </table>
                                </div>


                            </div>

                        </div>

                    </div>
                    <div class="column">
                        <div class="card">
                            <div class="message-header">
                                {{ __('pages/invoice.invoice_data') }}
                            </div>
                            <div class="message-body text-center">
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.total') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" value="{{ $purchase->invoice->total }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.discount') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" value="{{
                                            $purchase->invoice->discount_value
                                             }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.subtotal') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" readonly="" value="{{
                                            $purchase->invoice->subtotal }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.tax') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" value="{{ $purchase->invoice->tax }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.net') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" value="{{ $purchase->invoice->net }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>


                                {{--                                @foreach($purchase->invoice->expenses as $expense)--}}
                                {{--                                    <div class="list-group-item">--}}
                                {{--                                        <div class="columns">--}}
                                {{--                                            <div class="column">{{ $expense->expense->locale_name  }}</div>--}}
                                {{--                                            <div class="column">--}}
                                {{--                                                <input type="text" class="input" value="{{ $expense->amount }}"--}}
                                {{--                                                       disabled="">--}}
                                {{--                                            </div>--}}
                                {{--                                        </div>--}}
                                {{--                                    </div>--}}

                                {{--                                @endforeach--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <!-- end box -->

@stop
