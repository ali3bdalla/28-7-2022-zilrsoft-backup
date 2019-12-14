<!DOCTYPE html>
<html>
<head>
    <title>{{auth()->user()->organization->title_ar}}</title>

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
            margin: 10px;
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
            font-family: 'El Messiri', sans-serif !important;


        }

        .background_primary {
            background-color: #0859a4;
            padding: 10px;
            margin-bottom: 10px;
        }

        .company-info {
            margin-bottom: 5px;
        }


        .total_numbers .label {
            text-align: right !important;
        }

        .row {
            margin-top: 15px !important;
        }


        .directionfordate {
            direction: rtl !important;
        }

        /*.col-md-6 {*/
        /*      float: rig !important;*/
        /*  }*/


        .columns {
            font-size: 22px !important;
        }


    </style>


</head>

<body lang="ar" style="">
<div style="position: absolute;width: 100%;height: 100%;z-index: 100">

</div>
<div class="">

    {{--    <div class="header">--}}
    <div class="row">

        <div class="col-md-6">

        </div>
        <div class="col-md-6 text-right">
            <img src="{{asset(auth()->user()->organization->logo)}}" class="logo">
        </div>
    </div>


    <div class="background_primary">
        <div class="row">

            <div class="col-md-6" style="float: left">
                <h3 style="color: white">@if($payment->payment_type=='receipt'){{ __('pages/payments.receipt')}}@else{{ __('pages/payments.payment') }}@endif
                    {{ __('pages/invoice.number') }}
                    {{$payment->id }}
                </h3>
            </div>
            <div class="col-md-6 text-right" style="float: right">
                <div class="company-info">
                    <span>{{auth()->user()->organization->title_ar}}</span>
                    <span class="line"> | </span>
                    <span>{{auth()->user()->organization->vat}}</span>
                    <span class="line"> | </span>
                    <span>{{auth()->user()->organization->phone_number}}</span><br>
                    {{--                    <h3 class=>{{auth()->user()->organization->description}}</h3>--}}

                </div>
            </div>
        </div>

        <hr>
        <div class="row">

            <div class="col-md-6" style="float: right">

                <div class="company-info">
                    <span>{{ __('reusable.creator') }} : {{ $payment->creator->name  }}</span>
                </div>
                <div class="company-info">
                    <span>{{  __('pages/invoice.phone_number')  }} : {{  $payment->creator->user->phone_number}}</span>
                </div>
                <div class="company-info">
                    <span>{{ __('pages/invoice.department') }} :  {{ $payment->creator->department->title  }}</span>
                </div>


            </div>
            <div class="col-md-6 text-right" style="float: left">
                <div class="company-info" style="direction: ltr">
                    <span>{{ __('reusable.date') }} :<span class="directionfordate"> {{$payment->created_at}}</span>
                    </span>
                </div>
                <div class="company-info">
                    <span>{{$payment->steakholder_type }} : {{ $payment->steakholder_name  }}</span>
                </div>
                <div class="company-info">
                    <span>{{ __('pages/invoice.phone_number') }} :  {{ $payment->steakholder_phone_number  }}</span>
                </div>

            </div>
        </div>
    </div>


    <div class="content" style="padding: 22px;font-size: 22px">
        <div class="row">

            <div class="col-md-6">

            </div>
            <div class="col-md-6">
                <h5>{{__('pages/payments.payment_start_title_' . $payment->payment_type)}}{{__('pages/payments.voucher_user_' . $payment->user->user_title)}}

                    {{ $payment->user->name }}
                </h5>
            </div>
        </div>


        <div class="row">

            <div class="col-md-6"></div>

            <div class="col-md-6">
                <h5>{{__('pages/payments.amount_equ')}} {{ $payment->amount }}
                    ريال</h5>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6"></div>
            <div class="col-md-6">
                <h5>{{__('pages/payments.amount_in_words')}} {{ $payment->amount_ar_words }} </h5>
            </div>

        </div>


        <div class="row">

            <div class="col-md-6"></div>
            <div class="col-md-6">


                {{--                @if($payment->paymentable_type=='App\Invoice')--}}
                {{--                    @if( count($payment->paymentable->payments) >=2)--}}
                {{--                        <h5>{{__('pages/payments.payment_reason') }}  {{ __('pages/payments.payment_reason_2') }}--}}
                {{--                            ({{$payment->description }})--}}
                {{--                        </h5>--}}


                {{--                    @else--}}

                {{--                        <h5>{{__('pages/payments.payment_reason')}}  {{__('pages/payments.payment_reason_' .$payment->is_belongs_to_invoice)}}--}}
                {{--                            ({{$payment->description }})--}}
                {{--                        </h5>--}}
                {{--                    @endif--}}
                {{--                @else--}}
                {{--                    {{__('pages/payments.payment_reason')}}  {{__('pages/payments.payment_reason_' .--}}
                {{--                    $payment->paymentable_type=='App\Invoice')}}--}}
                {{--                @endif--}}
            </div>
        </div>
        {{--        @if($payment->is_belongs_to_invoice==1)--}}

        {{--        <div class="row">--}}
        {{--            <div class="col-md-6"></div>--}}
        {{--            <div class="col-md-6">--}}

        {{--                    {{__('pages/payments.invoices_numbers')}} ({{$payment->description }})--}}

        {{--            </div>--}}
        {{--        </div>--}}
        {{--        @endif--}}
        <hr>


        @if(!empty($payment->paymentable->locale_name))
            <div class="row">

                <div class="col-md-12">
                    <h5>{{__('pages/payments.paid_by_' . $payment->payment_type)}}
                        : {{ $payment->paymentable->locale_name}}
                        {{--                    @if($payment->paymentable->parent_id>=1){{--}}
                        {{--                                   $payment->paymentable->parent->locale_name--}}
                        {{--                                   }} - @endif--}}
                    </h5>
                </div>
            </div>

        @endif

        @if($payment->slug=='transfer' && $payment->payment_type=='payment')
            <div class="row">
                <div class="col-md-12">
                    <h5>{{__('pages/payments.user_account')}}
                        : {{ $payment->user_gateway->locale_name }}</h5>
                </div>
            </div>


        @endif

        @if($payment->description!='')
            <div class="row">
                <div class="col-md-12">
                    <h5>{{__('pages/payments.payment_description')}}
                        : {{ $payment->description }}</h5>
                </div>
            </div>


        @endif

    </div>


</div>

<footer>
    <div class="">
        <div class="end"> {{ auth()->user()->organization->title }}</div>
        <div class="text-center">
            <div class="col-xs-12">
                <div class="text-center">
                <span class="header_title total_header">{{ auth()->user()->organization->city_ar  }} - {{
                auth()->user()->organization->address_ar }}</span>
                </div>
            </div>
        </div>

        <div class="text-center"> {{ auth()->user()->organization->vat }}</div>
    </div>
</footer>

</div>
</body>

</html>


<script>
    // print();
</script>