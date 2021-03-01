<!DOCTYPE html>
<html>
<head>
    <title>{{$payment->id}}</title>

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
            margin-left: 20px;
            text-align: right;
        }


        .total {
            background-color: white !important;
            color: black !important;
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
            <img src="{{ auth()->user()->organization->logo }}" class="logo">
        </div>
    </div>


    <div class="background_primary">
        <div class="row">

            <div class="col-md-6" style="float: left">
                <h3 style="color: white">@if($payment->payment_type=='receipt'){{ __('pages/vouchers.receipt')}}@else{{ __('pages/vouchers.payment') }}@endif
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
                    <span>{{ __('reusable.creator') }} : {{ $payment->creator->locale_name  }}</span>
                </div>
                <div class="company-info">
                    <span>{{  __('pages/invoice.phone_number')  }} : {{  $payment->creator->user->phone_number}}</span>
                </div>
                <div class="company-info">
                    <span>{{ __('pages/invoice.department') }} :  {{ $payment->creator->department->locale_title
                    }}</span>
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
                <h5>{{__('pages/vouchers.payment_start_title_' . $payment->payment_type)}}{{__('pages/vouchers.voucher_user_' . $payment->user->user_title)}}

                    {{ $payment->user->locale_name }}
                </h5>
            </div>
        </div>


        <div class="row">

            <div class="col-md-6"></div>

            <div class="col-md-6">
                <h5>{{__('pages/vouchers.amount_equ')}} {{ displayMoney($payment->amount) }}
                    ريال</h5>
            </div>
        </div>

        <div class="row">

            <div class="col-md-6"></div>
            <div class="col-md-6">
                <h5>{{__('pages/vouchers.amount_in_words')}} {{ $payment->amount_ar_words }} </h5>
            </div>

        </div>

        @if(!empty($payment->paymentable->locale_name))
            <div class="row">

                <div class="col-md-12">
                    <h5>{{__('pages/vouchers.paid_by_' . $payment->payment_type)}}
                        : {{ $payment->paymentable->locale_name}}
                    </h5>
                </div>
            </div>

        @endif

        @if($payment->slug=='transfer' && $payment->payment_type=='payment')
            <div class="row">
                <div class="col-md-12">
                    <h5>{{__('pages/vouchers.user_account')}}
                        : {{ $payment->user_gateway->locale_name }}</h5>
                </div>
            </div>


        @endif

        @if($payment->description!='')
            <div class="row">
                <div class="col-md-12">
                    <h5>{{__('pages/vouchers.payment_description')}}
                        : {{ $payment->description }}</h5>
                </div>
            </div>

        @endif
    </div>

</div>

<footer style="">
    <div class="">
        <div class="row" style="color: black !important;">
            <div class="stamp">

            </div>
            <div class="issued_by">
                <h3 style="margin-bottom: 9px">{{__('reusable.issued_by')}}</h3>
                <p style="margin-bottom: 2px">{{ $payment->creator->locale_name }}</p>

            </div>
            <div class="clear"></div>
        </div>
        <div class="end" style="padding-top: 10px"> {{ auth()->user()->organization->city_ar }}
            - {{ auth()->user()->organization->address_ar }}</div>
        <div style="padding-top: 10px;text-align: center"> سعدنا بخدمتك</div>
    </div>
</footer>


</body>
</html>
<script>
    print();
</script>
