<!DOCTYPE html>
<html>
<head>
    <title>{{ $invoice->invoice_number }}</title>
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="content-type" content="text-html; charset=utf-8">
    <link rel="stylesheet" href="{{ asset('template/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{ asset('template/css/pdf-rtl.css')}}">
    <link href="https://fonts.googleapis.com/css?family=El+Messiri&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template/css/pdf.css') }}">
    <style>
        body {
            padding: 10px;
        }

        tr th, tr td {
            padding: 4px !important;
            text-align: center !important;

        }

        . {
            padding: 10px
        }

        .company-info {
            color: white !important;
        }

        * {
            /*font-family: Arial !important;*/
            font-family: 'El Messiri', sans-serif !important;


        }

        .background_primary {
            background-color: #777777;
            padding: 10px;
            margin: 2px;
            margin-bottom: 10px;

        }

        section table tbody.body .no, .total_numbers .number {
            background-color: #777777;
        }

        .company-info {
            margin-bottom: 5px;
        }


        .total_numbers {
            width: 200px;
            margin: 3px;
        }

        .total_numbers .label {
            padding: 5px;
            padding-top: 8px;
            text-align: right !important;
            line-height: 1em;
        }


        .total_numbers .value {
            line-height: 1em;
            padding: 5px;
            padding-top: 5px;
        }

        .issued_by {
            margin-right: 50px;
            text-align: right;
        }


        .total {
            background-color: white !important;
            color: black !important;
        }
    </style>


</head>

<body lang="ar">
<div id="app">
    <div class="row">
        <div class="col-md-6" style="float: left;padding-top: 10px;color: black !important;">
            <h5>{{ __('pages/invoice.branch') }} :
                {{$invoice->branch->locale_name }}
            </h5>
            <h5 style="margin: 10px 0px !important;">?????????? ?????????????? : {{$invoice->creator->organization->vat}}</h5>

            <h5 style="margin: 10px 0px !important;"> ?????????? ?????????????? : {{$invoice->creator->organization->cr}}</h5>
            <h5 style="margin: 10px 0px !important;"> ?????? ????????????
                : {{$invoice->creator->organization->phone_number}}</h5>

        </div>
        <div class="text-right col-md-6">
            <img src="{{ $invoice->creator->organization->logo }}" class="logo">
        </div>
    </div>


    <div class="">
        <div class="row" style="color:black !important;font-weight: bolder !important;font-size: 19px">


            <div class="text-right col-md-12" style="float: right">
                <div class="company-info" style="color: black !important; margin-top: -30px;">
                    <span style="padding-right: 13px;font-size: 25px">{{$invoice->creator->organization->title_ar}}</span>
                    <p style="padding-right: 13px;font-size: 20px;margin-top:10px">{{$invoice->creator->organization->description_ar}}</p>

                </div>
            </div>
        </div>

        <br>
        {{--        <hr>--}}
        <div class="row background_primary">

            <div class="text-right col-md-6" style="float: right">
                <div class="company-info" style="margin-bottom: 8px">
                    <span>{{ __('pages/invoice.date') }} :
                        <span style="direction: ltr !important;
                    display: inline-block">{{
                    $invoice->created_at
                    }}</span></span>
                </div>


                <div class="company-info" style="margin-bottom: 10px">
                    <span>{{$invoice->user_type }} : {{ $invoice->final_user_name  }}</span>
                </div>
                <div class="company-info">
                    <span>{{ __('pages/invoice.phone_number') }} :  {{ $invoice->user->phone_number  }}</span>
                </div>

            </div>


            <div class="col-md-6" style="float: left">

                <div class="company-info" style="margin-bottom: 10px">
                    <span>{{ __('pages/invoice.invoice') }}
                        {{$invoice->description }} {{ __('pages/invoice.number') }} :
                        {{$invoice->invoice_number }}</span>
                </div>
                <div class="company-info" style="margin-bottom: 10px">
                    <span>{{$invoice->manager_type }} : {{ $invoice->manager->locale_name
                    }}</span>
                </div>
                <div class="company-info">
                    <span>{{ __('pages/invoice.department') }} :  {{ $invoice->department->locale_title  }}</span>
                </div>


            </div>

        </div>
    </div>


    <section>

        <div class="">
            <div class="table-wrapper">
                <table>
                    <tbody class="head" style="background-color: black !important;">
                    <tr>
                        <th class="no">#</th>
                        <th class="desc" style="width: 180px !important;background-color: #777777 !important;">{{__
                        ('pages/invoice.item_name')}}</th>
                        <th class="unit"
                            style="width: 30px !important;background-color: #777777 !important;">
                            {{__('pages/invoice.qty')}}</th>
                        @if($invoice->show_items_price_in_print_mode)
                            <th class="unit"
                                style="width: 50px !important;background-color: #777777 !important;">{{__('pages/invoice.price')}}</th>
                            <th class="unit"
                                style="width: 70px !important;background-color: #777777 !important;">{{__('pages/invoice.total')}} </th>
                            <th class="unit"
                                style="width: 40px !important;background-color: #777777 !important;">
                                {{__('pages/invoice.discount')}} </th>
                            <th class="unit"
                                style="width: 40px !important;background-color: #777777 !important;">
                                {{__('pages/invoice.tax')}} </th>
                            <th class="unit"
                                style="width: 70px !important;background-color: #777777 !important;">????????????????
                            </th>
                        @endif
                    </tr>
                    </tbody>
                    <tbody class="body">
                        @foreach($invoice->printedItems() as $item)
                            @if($loop->index%2==0)
                                <?php $background_color = '#ffffff'; ?>
                                <tr>
                            @else
                                <tr style="background-color: #8888">
                                <?php $background_color = '#eee'; ?>
                            @endif

                                <td class="no" style="width:30px !important;">{{$loop->index + 1}}</td>
                                <td class="desc" style="width: 10%  !important;text-align: right !important;
                                        font-weight: bold;font-size: 13px !important;color: black;background-color:
                                <?php echo $background_color; ?> !important;">{{mb_substr($item->item->locale_name, 0,55) }}</td>
                                <td class="total" style="background-color:
                                <?php echo $background_color; ?> !important;">{{ $item->qty }}</td>
                                @if($invoice->show_items_price_in_print_mode)
                                    <td class="total" style="background-color:
                                    <?php echo $background_color; ?> !important;">{{ moneyFormatter($item->price) }}</td>
                                    <td class="total" style="background-color:
                                    <?php echo $background_color; ?> !important;"> {{ moneyFormatter($item->total) }}</td>
                                    <td class="total" style="background-color:
                                    <?php echo $background_color; ?> !important;"> {{ moneyFormatter($item->discount) }}</td>
                                    <td class="total" style="background-color:
                                    <?php echo $background_color; ?> !important;"> {{ moneyFormatter($item->tax) }}</td>
                                    <td class="total" style="background-color:
                                    <?php echo $background_color; ?> !important;"> {{ moneyFormatter($item->net) }}</td>
                                @endif
                            </tr>
                            @if(!in_array($item->invoice_type,['purchase']))
                                @if($item->item->is_need_serial)
                                    @foreach($item->item->serials()
                                    ->where([
                                    ["sale_id",$invoice->id],
                                    ["item_id",$item->item->id],
                                    ])
                                    ->orWhere([["return_sale_id",$invoice->id],["item_id",$item->item->id]])
                                    ->orWhere([["return_purchase_id",$invoice->id],["item_id",$item->item->id]])
                                    ->orWhere([["purchase_id",$invoice->id],["item_id",$item->item->id]])
                                    ->get() as $index => $serial
                                    )
                                        <tr style="background-color: #8888">

                                            <td class="" style="width:30px !important;">S/N</td>
                                            <td class="desc"
                                                style="width: 10%  !important;text-align: right !important;
                                                        font-weight: bold;font-size: 10px !important;color: black;
                                                        background-color:
                                                <?php echo $background_color; ?> !important;padding-right: 20px !important;">{{$serial->serial }}</td>
                                            <td class="total" style="background-color:
                                            <?php echo $background_color; ?> !important;"></td>
                                            @if($invoice->show_items_price_in_print_mode)
                                                <td class="total" style="background-color:
                                                <?php echo $background_color; ?> !important;"></td>
                                                <td class="total" style="background-color:
                                                <?php echo $background_color; ?> !important;"></td>
                                                <td class="total" style="background-color:
                                                <?php echo $background_color; ?> !important;"></td>
                                                <td class="total" style="background-color:
                                                <?php echo $background_color; ?> !important;"></td>
                                                <td class="total" style="background-color:
                                                <?php echo $background_color; ?> !important;"></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </section>
    <div>
        @php
            $show_price_in_print_mode_tax_net = $invoice->show_price_in_print_mode_tax_and_net;
        @endphp
        <div class="text-right total_numbers">
            <div class="">
                <h4 style="color: white;font-size: 22px;padding: 4px">{{ __('pages/invoice.invoice_data') }}</h4>
            </div>

            <div class="number">
                <div class="label">{{ __('pages/invoice.total') }} :</div>
                <div class="value"> {{ moneyFormatter($invoice->total)
        }}</div>
                <div class="clear"></div>
            </div>
            <div class="number">
                <div class="label">{{ __('pages/invoice.discount') }} :</div>
                <div class="value">{{ moneyFormatter($invoice->discount)
        }}</div>
                <div class="clear"></div>
            </div>

            <div class="number">
                <div class="label">{{ __('pages/invoice.subtotal') }} :</div>
                <div class="value">{{moneyFormatter( $invoice->subtotal)}}</div>
                <div
                        class="clear"></div>
            </div>

            <div class="number">
                <div class="label">{{ __('pages/invoice.vat') }} (15%) :
                </div>
                <div class="value"> {{ moneyFormatter($invoice->tax)}}</div>
                {{--                ({{ $invoice->getVat('purchase') }}%)--}}
                <div class="clear"></div>
            </div>
            <div class="number">
                <div style="text-align: center;">????????????????</div>
                <div style="margin: 10px;text-align: center;font-size: 25px">
                    <h3 class=><b>
                            {{ moneyFormatter($invoice->net) }}</b></h3>
                </div>
                <div class="clear"></div>
            </div>

        </div>


    </div>
    <div class="text-right total_numbers " style="float:right;color: black !important;">
        <div class="">
            <h4 style="color: white;font-size: 22px;padding: 4px">{{ __('pages/invoice.invoice_data') }}</h4>
        </div>

        <div class="number">
            <div class="label">{{ __('pages/invoice.items_count') }} :</div>
            <div class="value"> {{ $invoice->items->count()
        }}</div>
            <div class="clear"></div>
        </div>
        <div class="number">
            <div class="label">{{ __('pages/invoice.items_qty_count') }} :</div>
            <div class="value">{{$invoice->printedItemsQuantity()}}</div>
            <div class="clear"></div>
        </div>


        <div class="">
            @if($invoice->notes!="")
                <div class="">
                    <h2 style="font-size: 23px;
    margin-top: 19px;
    margin-bottom: 5px;">??????????????:</h2>
                    {{ mb_substr($invoice->notes,0,255) }}
                </div>
            @endif
            <div class="" style="margin-top: 10px">
                <h3 style="font-weight: bolder;margin-top: 5px;margin-bottom: 5px">???????????? ????????????????</h3>
                <p>* ?????????????? ?????????????? ?????????? ?????? ???????????? ?????? ?????????? .</p>
                <p>* ?????????????? ???????? ?????????? ???????? .</p>
                <p>* ?????????????? ???????? ???????? ???????? .</p>

                @foreach($invoice->items as $item)
                    @if($item->belong_to_kit==false &&  $item->item != null && $item->show_price_in_print_mode && $item->item->warranty)
                        <p>* ?????????? {{$loop->index + 1 }} {{ $item->item->warranty_title }} .</p>
                    @endif
                @endforeach


            </div>
        </div>

    </div>
    <div class="pull-left" style="margin-top:10px">{!! $invoice->tlvQrCode(); !!}</div>
</div>

<img src="{{ $invoice->getBackgroundAssetAttribute() }}">
<footer>
    <div>
        <div class="row" style="color: black !important;">
            <div class="stamp">
            </div>
            <div class="issued_by">
                @if($invoice->creator->organization->stamp != null)
                    <div style="margin-bottom: 9px"><img src="{{$invoice->creator->organization->stamp}}"
                                                         style="width: 80px"/></div>

                @endif
                <h3 style="margin-bottom: 9px">{{__('reusable.issued_by')}} {{ $invoice->creator->locale_name }}</h3>
            </div>
            <div class="clear"></div>
        </div>
        <div class="end" style="padding-top: 10px"> {{ $invoice->creator->organization->city_ar }} - {{ $invoice->creator->organization->address_ar }}</div>
        <div style="padding-top: 10px;text-align: center"> ?????????? ????????????</div>
    </div>
</footer>
</body>
</html>
<script type="text/javascript">
  print()
</script>
