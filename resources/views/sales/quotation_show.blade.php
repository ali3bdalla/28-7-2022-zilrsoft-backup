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
                    <a href="{{ route('management.sales.clone',$sale->id) }}" class="button is-primary"> تحويل الى
                        فاتورة</a>
                </div>
                <div class="column">


                    <print-invoice-component :invoice_id="{{$sale->invoice_id}}"
                                             :print="false"
                                             :title='@json( __('pages/invoice.price_invoice'))'></print-invoice-component>


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

                    </div>


                    <div class="column">
                        <div class="card">
                            {{--                            <div class="message-header">--}}
                            {{--                                invoice data--}}
                            {{--                            </div>--}}
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


                                {{--                                @foreach($sale->invoice->expenses as $expense)--}}
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
