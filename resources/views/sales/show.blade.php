@extends('layouts.master2')


@section('title',__('pages/invoice.view_sale'))
@section('desctipion','create sale invoice')
@section('route',route('management.sales.index'))


@section('content')
    <div class="message">


        <div class="message-header has-background-dark">
            <h4 class="title has-text-white pull-right">{{$sale->prefix . $sale->invoice_id}}</h4>

            <div class="columns">
                <div class="column">
                    @if(isset($_GET['ask']) && $_GET['ask']=='receipt')
                        <receipt-printer-component :print="true" :invoice_id='@json($sale->invoice_id)
                                '></receipt-printer-component>
                    @else
                        <receipt-printer-component :print="true" :invoice_id='@json($sale->invoice_id)
                                '></receipt-printer-component>
                    @endif
                </div>
                <div class="column">

                    {{--                    @if(isset($_GET['ask']) && $_GET['ask']=='a4')--}}
                    {{--                        <print-invoice-component :invoice_id="{{$sale->invoice_id}}"--}}
                    {{--                                                 :print="true"--}}
                    {{--                                                 :title='@json( __('pages/invoice.price_invoice'))'></print-invoice-component>--}}
                    {{--                    @else--}}
                    <print-invoice-component :invoice_id="{{$sale->invoice_id}}"
                                             :print="false"
                                             :title='@json( __('pages/invoice.price_invoice'))'></print-invoice-component>
                    {{--                    @endif--}}


                </div>
                <div class="column">
                    @if($sale->invoice_type=='sale')

                        <a href="{{ route('management.sales.edit',$sale->id) }}" class="button is-warning"><i
                                    class="fa
                        fa-redo"></i>&nbsp; {{ __('pages/invoice.return')
                        }}</a>&nbsp;

                    @endif
                </div>
            </div>

            {{--             --}}
        </div>
        <div class="message-body">
            <div class="has-background-light">
                <div class="columns">
                    <div class="column">
                        <!-- <label>client name</label> -->
                        <div class="">
                            <div class="input-group">
                                <span class="input-group-addon" id="vendors-list">{{ __('pages/invoice.client')
                                }}</span>

                                <input type="text" name="" aria-describedby="time-field" class="form-control"
                                       disabled="" value="{{$sale->client->name}}">

                            </div>


                        </div>
                    </div>

                    <div class="column">
                        <!-- <label>date</label> -->
                        <div class="input-group">
                            <span class="input-group-addon" id="time-field">{{ __('reusable.date')
                                }}</span>
                            <input type="text" name="" style="text-align: center !important; direction: ltr
                            !important;" aria-describedby="time-field" class="form-control" disabled=""
                                   value="{{$sale->created_at}}">

                        </div>


                    </div>


                </div>


                <div>
                    <div class="columns">
                        <div class="column">
                            <!-- <label>client name</label> -->
                            <div class="">
                                <div class="input-group">
                                    <span class="input-group-addon" id="receivers-list">{{ __('pages/invoice.salesman')
                                }}</span>
                                    <input type="text" name="" aria-describedby="receivers-list" class="form-control"
                                           disabled="" value="{{$sale->salesman->name}}">

                                </div>

                            </div>
                        </div>

                        <div class="column">
                            <div class="input-group">
                                <span class="input-group-addon" id="department-field">{{ __('pages/invoice.department')
                                }}</span>
                                <input type="text" name="" aria-describedby="department-field" readonly
                                       class="form-control" disabled=""
                                       value="{{$sale->invoice->branch->name}}">

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
                        <th class="has-text-white">#</th>
                        <!-- <th class="has-text-white"></th> -->
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


				<?php $index = 1;?>
                    @foreach($sale->invoice->items as $item)
                        @if(!$item->item->is_expense)
                            <tr @if($item->belong_to_kit==true) style="background-color: #48dbfb;color: white !important;" @endif>
                                <th class="has-text-dark">
                                    @if($item->belong_to_kit!=true)  {{ $index}}
							  <?php $index++;?>
                                    @endif
                                </th>
                                <!-- <th class="has-text-white"></th> -->
                                <th text="item.barcode"
                                    style="text-align: left !important;">{{ $item->item->barcode }}</th>
                                <th text="item.name"
                                    style="text-align: right !important;">{{ $item->item->locale_name }}</th>
                                <th width="6%">
                                    <input type="text" class="input" value="{{ $item->qty }}" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" value="{{ $item->price }}" disabled="">

                                </th>
                                <th class="has-text-white">
                                    @if($item->item->is_kit)
                                        <input type="text" class="input" value="{{ money_format("%i",0) }}" disabled="">
                                    @else
                                        <input type="text" class="input" value="{{ $item->total }}" disabled="">

                                    @endif
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="discount"
                                           value="@if(!$item->item->is_kit){{ $item->discount }}@else {{ money_format("%i",0) }} @endif"
                                           disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="subtotal" readonly="" value="{{
                                $item->subtotal }}" disabled="">
                                </th>
                                <th class="has-text-white">
                                    <input type="text" class="input" placeholder="vat sale" readonly="" value="{{
                                $item->item->vts }}%" disabled="">
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
                                        @foreach($sale->invoice->items as $item)
                                            @if($item->item->is_expense)
                                                <tr @if($item->belong_to_kit==true) style="background-color: #48dbfb;color: white !important;" @endif>

                                                    <!-- <th class="has-text-white"></th> -->

                                                    <th text="item.name"
                                                        style="text-align: right !important;">{{ $item->item->locale_name }}</th>

                                                    <th class="has-text-white">
                                                        <input type="text" class="input" value="{{ $item->price }}"
                                                               disabled="">

                                                    </th>

                                                    <th class="has-text-white">
                                                        <input type="text" class="input" placeholder="tax" readonly=""
                                                               value="{{ $item->tax
                                }}" disabled="">
                                                    </th>
                                                    <th class="has-text-white">
                                                        <input type="text" class="input" placeholder="net" readonly=""
                                                               value="{{ $item->net
                                }}" disabled="">
                                                    </th>

                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                            <div class="panel panel-primary">


                                <div class="panel-body">
                                    @if(!empty($sale->invoice->payments))
                                        @foreach($sale->invoice->payments as $payment)

                                            <div class="form-group">

                                                <div class="input-group">
                                                    <span id="1" class="input-group-addon" style="min-width: 250px;
                                                    font-weight: bolder;">
                                                        {{$payment->name}}
                                                        &nbsp;&nbsp; ( <a target="_blank" href="{{ route('management.payments.show',
                                                        $payment->id) }}">عرض السند</a> )
                                                    </span>
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
                                                    <h1 class="title text-center">{{$sale->invoice->net}}</h1></div>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">المدفوع</span>
                                                    <h1 class="title text-center">{{money_format("%i",
                                                $sale->invoice->net - $sale->invoice->remaining)}}</h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column">
                                            <div class="card">
                                                <div class="card-content"><span
                                                            style="font-weight: bolder; font-size: 27px;">المتبقي / آجل</span>
                                                    <h1 class="title text-center"><input disabled="disabled"
                                                                                         value="{{ $sale->invoice->remaining}}"
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


                                        @if($sale->invoice_type=='sale')

                                            @foreach($transactions as $transaction)



                                                {{--                                            @if($transaction['description']=='to_item' || $transaction['description']=='to_tax')--}}
                                                @if(!in_array($transaction['description'],['to_cogs','to_gateway',
                                                'to_products_sales_discount','to_services_sales_discount',
                                                'to_other_services_sales_discount','to_stock']))

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

                                            @endforeach
                                        @else



                                            @foreach($transactions as $transaction)



                                                {{--                                            @if($transaction['description']=='to_item' || $transaction['description']=='to_tax')--}}
                                                @if(in_array($transaction['description'],['to_cogs','to_gateway',
                                                'to_products_sales_discount','to_services_sales_discount',
                                                'to_other_services_sales_discount','to_stock']))

										   <?php $total_credit = $total_credit + $transaction['amount']?>
                                                     {{--                                                    <tr>--}}
                                                     {{--                                                        <td class="datedirection">{{ $transaction->created_at }}</td>--}}
                                                     {{--                                                        <td>{{ $transaction->debitable->locale_name }}</td>--}}
                                                     {{--                                                        <td>{{money_format("%i", $transaction->amount) }}</td>--}}
                                                     {{--                                                        <td></td>--}}
                                                     {{--                                                    </tr>--}}
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
                                                         <td> {{
                                                         $transaction->debitable->locale_name }}</td>
                                                         <td>{{money_format("%i", $transaction->amount) }}</td>
                                                         <td></td>
                                                     </tr>

                                                @endif

                                            @endforeach
                                        @endif
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

                            <div class="message-body text-center">
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.total') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" value="{{ $sale->invoice->total }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.discount') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" value="{{
                                            $sale->invoice->discount_value
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
                                            $sale->invoice->subtotal }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.tax') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" value="{{ $sale->invoice->tax }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="columns">
                                        <div class="column">{{ __('pages/invoice.net') }}</div>
                                        <div class="column">
                                            <input type="text" class="input" value="{{ $sale->invoice->net }}"
                                                   disabled="">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>
    <!-- end box -->



@stop
