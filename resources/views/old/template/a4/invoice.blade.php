<!DOCTYPE html>
<html>
<head>
    <title>{{$invoice->title }}</title>

    <!-- Latest compiled and minified CSS -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,700&subset=latin,latin-ext'
          rel='stylesheet' type='text/css'>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="content-type" content="text-html; charset=utf-8">
    <link rel="stylesheet" href="{{ asset('template/css/bootstrap.css')}}">
    <link rel="stylesheet" href="{{ asset('template/css/pdf-rtl.css')}}">

    <link href="https://fonts.googleapis.com/css?family=El+Messiri&display=swap" rel="stylesheet">

    {{--        <link rel="stylesheet" href="{{ asset('template/css/pdf.css') }}">--}}


    <style>
        body {
            padding: 10px;
            padding-bottom: 0px !important;
            margin-bottom: 0px !important;
        }

        tr th, tr td {
            padding: 4px !important;
            /*font-size: 16px !important;*/
            /*font-weight: bold !important;*/
            text-align: center !important;

        }

        {{--.centerd_data {--}}
        {{--    height: 200px;--}}
        {{--    background: url({{ asset('template/images/paid.png') }}) no-repeat no-repeat center center;--}}
        {{--    /*background-image: url();*/--}}
        {{--    background-position: center center;--}}
        {{--}--}}


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

        section table tbody.body .no,.total_numbers .number {
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
            margin-left: 20px;
            text-align: right;
        }



        .total {
            background-color: white !important;
            color:black !important;
        }
    </style>


</head>

<body lang="ar" style="">
{{--<div style="position: absolute;width: 100%;height: 100%;">--}}
{{--    --}}{{--    background: url({{ $invoice->background_asset }}) no-repeat--}}
{{--    --}}{{--    no-repeat center center;z-index: 100--}}

{{--</div>--}}
<div class="" style="margin:10px">

    {{--    <div class="header">--}}
    <div class="row">

        <div class="col-md-6" style="float: left;padding-top: 10px;color: black !important;">
            <h5>{{ __('pages/invoice.branch') }} :
                {{$invoice->creator->branch->name }}
            </h5>
            <h5 style="margin: 10px 0px !important;">الرقم الضريبي : {{auth()->user()->organization->vat}}</h5>

            <h5 style="margin: 10px 0px !important;"> السجل التجاري : {{auth()->user()->organization->cr}}</h5>
            <h5 style="margin: 10px 0px !important;"> رقم الهاتف : {{auth()->user()->organization->phone_number}}</h5>

        </div>
        <div class="col-md-6 text-right">
            <img src="{{asset(auth()->user()->organization->logo)}}" class="logo">
        </div>
    </div>


    <div class="">
        <div class="row" style="color:black !important;font-weight: bolder !important;font-size: 19px">


            <div class="col-md-12 text-right" style="float: right">
                <div class="company-info" style="color: black !important; margin-top: -30px;">
                    <span style="padding-right: 13px;font-size: 25px">{{auth()->user()->organization->title_ar}}</span>
                    <p style="padding-right: 13px;font-size: 20px;margin-top:10px">{{auth()->user()
                    ->organization->description}}</p>

                </div>
            </div>
        </div>

        <br>
        {{--        <hr>--}}
        <div class="row background_primary">

            <div class="col-md-6 text-right" style="float: right">
                <div class="company-info" style="margin-bottom: 8px">
                    <span>{{ __('pages/invoice.date') }} :
                        <span style="direction: ltr !important;
                    display: inline-block">{{
                    $invoice->created_at
                    }}</span></span>
                </div>


                <div class="company-info" style="margin-bottom: 10px">
                    <span>{{$invoice->steakholder_type }} : {{ $invoice->steakholder_name  }}</span>
                </div>
                <div class="company-info">
                    <span>{{ __('pages/invoice.phone_number') }} :  {{ $invoice->steakholder_phone_number  }}</span>
                </div>

            </div>


            <div class="col-md-6" style="float: left">

                <div class="company-info" style="margin-bottom: 10px">
                    <span>{{ __('pages/invoice.invoice') }}
                        {{$invoice->description }} {{ __('pages/invoice.number') }} :
                        {{$invoice->title }}</span>
                </div>
                <div class="company-info" style="margin-bottom: 10px">
                    <span>{{$invoice->served_title }} : {{ $invoice->served_by
                    }}</span>
                </div>
                {{--                <div class="company-info"  style="margin-bottom: 8px">--}}
                {{--                    <span>{{  __('pages/invoice.phone_number')  }} : {{  $invoice->creator->user->phone_number  }}</span>--}}
                {{--                </div>--}}
                <div class="company-info">
                    <span>{{ __('pages/invoice.department') }} :  {{ $invoice->creator->department->title  }}</span>
                </div>


            </div>

        </div>
    </div>
    {{--    </div>--}}

    {{--    <header class="detials clearfix">--}}
    {{--        <div class="">--}}
    {{--            <div>--}}
    {{--                <div style="margin-bottom: 20px">--}}
    {{--                    <img class="logo" src="{{ asset('template/images/logo-color.png')}}" alt="">--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--            <div class="company-info">--}}
    {{--                <span>{{auth()->user()->organization->title}}</span>--}}
    {{--                <span class="line"> | </span>--}}
    {{--                <span>{{auth()->user()->organization->vat}}</span>--}}
    {{--                <span class="line"> | </span>--}}
    {{--                <span>{{auth()->user()->organization->title}}</span><br>--}}
    {{--                <h3 class=>{{auth()->user()->organization->description}}</h3>--}}

    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </header>--}}


    <section>
        {{--        <div class="details clearfix">--}}
        {{--            <div style="height: 100px"></div>--}}
        {{--    --}}
        {{--            <div class="client left" style="color: #fff;padding-top: 20px">--}}

        {{--                --}}{{--                <p>{{$invoice->steakholder_name}}</p>--}}
        {{--                --}}{{--                <h3 class="name"><strong> {{$invoice->steakholder_name}} </strong></h3>--}}
        {{--                --}}{{--                <p>{{$invoice->steakholder_name}}</p>--}}
        {{--                --}}{{--                <p>--}}
        {{--                {{$invoice->steakholder_type}}--}}
        {{--                --}}{{----}}{{--                </p>--}}
        {{--                <p class="customer-phone">{{$invoice->steakholder_phone_number}}</p>--}}
        {{--            </div>--}}
        {{--            <div class="data right" style="float:right;width:auto;display:block;color: white">--}}
        {{--                <div class="title">{{$invoice->title}}</div>--}}


        {{--                <div class="date">--}}
        {{--                    <div class="Quotationtype">--}}
        {{--                        <p>{{ $invoice->created_at }}</p>--}}
        {{--                    </div>--}}
        {{--                    {{ $invoice->created_date }}--}}
        {{--                    <br>--}}
        {{--                    {{ $invoice->branch->name }}--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div class="">
            <div class="table-wrapper">
                <table>
                    <tbody class="head" style="background-color: black !important;">
                    <tr>
                        <th class="no">#</th>
                        <th class="desc" style="width: 300px !important;background-color: #777777 !important;">{{__
                        ('pages/invoice.item_name')}}</th>
                        <th class="unit"
                            style="width: 50px !important;background-color: #777777 !important;">{{__('pages/invoice.qty')}}</th>
                        <th class="unit"
                            style="width: 50px !important;background-color: #777777 !important;">{{__('pages/invoice.price')}}</th>
                        <th class="unit"
                            style="width: 70px !important;background-color: #777777 !important;">{{__('pages/invoice.total')}} </th>
                        <th class="unit"
                            style="width: 70px !important;background-color: #777777 !important;">{{__('pages/invoice.discount')}} </th>
                        <th class="unit"
                            style="width: 70px !important;background-color: #777777 !important;">{{__('pages/invoice.tax')}} </th>
                        <th class="unit"
                            style="width: 70px !important;background-color: #777777 !important;">{{__('pages/invoice.net')}} </th>
                    </tr>
                    </tbody>
                    <tbody class="body">
				<?php $items_qty_count = 0; ?>
                    @if(!empty($invoice->items))
                        @foreach($invoice->items as $item)
					    <?php $items_qty_count = $items_qty_count + $item->qty ?>
                             @if($loop->index%2==0)
	                             <?php $background_color = "#ffffff"; ?>
                                 <tr>
                             @else
                                 <tr style="background-color: #8888">
                                     <?php $background_color = "#eee"; ?>
                             @endif
                                     <td class="no" style="width:30px !important;">{{$loop->index + 1}}</td>
                                     <td class="desc" style="width: 10%  !important;text-align: right !important;
                                font-weight: bold;font-size: 13px !important;color: black;background-color:
                                     <?php echo $background_color;?> !important;">{{
                                $item->item->locale_name }}</td>
                                     <td class="total" style="background-color:
                                     <?php echo $background_color;?> !important;">{{ $item->qty }}</td>
                                     <td class="total" style="background-color:
                                     <?php echo $background_color;?> !important;">{{ $item->price }}</td>
                                     <td class="total" style="background-color:
                                     <?php echo $background_color;?> !important;"> {{ $item->total }}</td>
                                     <td class="total" style="background-color:
                                     <?php echo $background_color;?> !important;"> {{ $item->discount }}</td>
                                     <td class="total" style="background-color:
                                     <?php echo $background_color;?> !important;"> {{ $item->tax }}</td>
                                     <td class="total" style="background-color:
                                     <?php echo $background_color;?> !important;"> {{ $item->net }}</td>
                                 </tr>

                                 @endforeach
                             @endif

                    </tbody>
                </table>
            </div>

        </div>
    </section>
    <div class="">
        <div class="total_numbers text-right">
            <div class="">
                <h4 style="color: white;font-size: 22px;padding: 4px">{{ __('pages/invoice.invoice_data') }}</h4>
            </div>

            <div class="number">
                <div class="label">{{ __('pages/invoice.total') }} :</div>
                <div class="value"> {{ $invoice->total
        }}</div>
                <div class="clear"></div>
            </div>
            <div class="number">
                <div class="label">{{ __('pages/invoice.discount') }} :</div>
                <div class="value">{{ $invoice->discount_value
        }}</div>
                <div class="clear"></div>
            </div>

            <div class="number">
                <div class="label">{{ __('pages/invoice.subtotal') }} :</div>
                <div class="value">{{ $invoice->subtotal}}</div>
                <div
                        class="clear"></div>
            </div>

            <div class="number">
                <div class="label">{{ __('pages/invoice.vat') }} :
                </div>
                <div class="value"> {{ $invoice->tax
        }}</div>
                {{--                ({{ $invoice->getVat('purchase') }}%)--}}
                <div class="clear"></div>
            </div>
            <div class="number">
                <div class="label">{{ __('pages/invoice.net') }} :</div>
                <h3 class="value"><b>
                        {{ $invoice->net }}</b></h3>
                <div class="clear"></div>
            </div>

        </div>


    </div>

    <div class="total_numbers text-right " style="float:right;color: black !important;">
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
            <div class="value">{{$items_qty_count}}</div>
            <div class="clear"></div>
        </div>

        {{--        <div class="number">--}}
        {{--            <div class="label">{{ __('pages/invoice.subtotal') }} :</div>--}}
        {{--            <div class="value">{{ $invoice->subtotal}}</div>--}}
        {{--            <div--}}
        {{--                    class="clear"></div>--}}
        {{--        </div>--}}

        {{--        <div class="number">--}}
        {{--            <div class="label">{{ __('pages/invoice.vat') }} :--}}
        {{--            </div>--}}
        {{--            <div class="value"> {{ $invoice->tax--}}
        {{--        }}</div>--}}
        {{--            --}}{{--                ({{ $invoice->getVat('purchase') }}%)--}}
        {{--            <div class="clear"></div>--}}
        {{--        </div>--}}
        {{--        <div class="number">--}}
        {{--            <div class="label">{{ __('pages/invoice.net') }} :</div>--}}
        {{--            <h3 class="value"><b>--}}
        {{--                    {{ $invoice->net }}</b></h3>--}}
        {{--            <div class="clear"></div>--}}
        {{--        </div>--}}

    </div>


</div>


<footer style="">
    <div class="">


        {{--            @if(!empty($invoice->payments))--}}
        {{--                <div class="bank_details" style="margin-bottom: 14px">--}}
        {{--                    <h3>{{__('pages/invoice.payments')}}</h3>--}}
        {{--                </div>--}}

        {{--                @foreach($invoice->payments as $payment)--}}

        {{--                    <div class="bank_details" style="direction: rtl;margin-bottom: 10px" >--}}
        {{--                        <h3><b>{{ $payment->payment->gateway->name }}</b> (<span style="color: black;font-weight:--}}
        {{--                        bolder">{{$payment->amount}}</span> ريال)</h3>--}}
        {{--                    </div>--}}


        {{--                @endforeach--}}
        {{--            @endif--}}

        <div class="row" style="color: black !important;">
            <div class="stamp">
                {{--                    <h3>{{__('pages/invoice.current_status')}}</h3>--}}
                {{--                    <img style="width:100px;margin:5px auto 0;" src="{{ $invoice->background_asset }}">--}}
            </div>
            <div class="issued_by">
                <h3 style="margin-bottom: 9px">{{__('reusable.issued_by')}}</h3>
                <p style="margin-bottom: 2px">{{ $invoice->creator->name }}</p>
                <p style="margin-bottom: 2px">{{ $invoice->creator->user->phone_number }}</p>
                <p>{{ $invoice->creator->email }} </p>
            </div>
            <div class="clear"></div>
        </div>
        <div class="end" style="padding-top: 10px"> {{ auth()->user()->organization->city_ar }} -  {{ auth()->user()
        ->organization->address_ar }}</div>
        {{--        <div class="text-center"> {{ auth()->user()->organization->vat }}</div>--}}
    </div>
</footer>

</div>
</body>

</html>


<script>
    print();
</script>