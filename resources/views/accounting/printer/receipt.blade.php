<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css" rel="stylesheet"
      id="bootstrap-css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

{{--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">--}}
{{--<link href="https://fonts.googleapis.com/css?family=El+Messiri&display=swap" rel="stylesheet">--}}
<style>


    .invoice-title h2, .invoice-title h3 {
        display: inline-block;
    }

    .table > tbody > tr > .no-line {
        border-top: none;
    }

    .table > thead > tr > .no-line {
        border-bottom: none;
    }

    .table > tbody > tr > .thick-line {
        /*border-top: 2px solid;*/
    }

    * {
        /*font-family: 'El Messiri', sans-serif !important;*/
        font-size: 12px !important;
        font-weight: bold !important;

    }

    .header_title span {
        font-size: 18px;
    }

    .total_header {
        font-size: 20px;
    }

    tbody {
        border-bottom: 2px solid black !important;

    }


</style>


<div class="container-fluid" id="container">

    <div class="raw">

        <div class="col-xs-12">
            <div align="center" style="font-size: 27px !important;">{{
                $invoice->organization->title_ar }}</div>

        </div>

    </div>

    <div class="row">
        <div class="col-sm-12 text-center">
            رقم الفاتورة
        </div>
        <div class="col-xs-12 text-center" style="font-size: 35px !important;">
            {{$invoice->invoice_number }}
        </div>
    </div>

    <div class="row">wb
        <div class="col-xs-6"> اسم العميل</div>
        <div class="col-xs-6 text-left">
            {{ $invoice->user_alice_name == "" ? $invoice->user->locale_name :$invoice->user_alice_name   }}</div>
    </div>


    <div class="row">
        <div class="col-xs-6"> التاريخ</div>
        <div class="col-xs-6 text-left " style="direction: ltr"> {{$invoice->created_at }}</div>
    </div>


    <div class="row ">
        <div class="col-xs-6"> الفرع</div>
        <div class="col-xs-6 text-left " style="direction: ltr"> {{$invoice->creator->branch->locale_name }}</div>
    </div>


    <div class="row ">
        <div class="col-xs-6"> القسم</div>
        <div class="col-xs-6 text-left " style="direction: ltr"> {{$invoice->creator->department->locale_title }}</div>
    </div>


    <div class="row">
        <div class="col-xs-6"> البائع</div>
        <div class="col-xs-6 text-left " style="direction: ltr"> {{$invoice->manager->locale_name }}</div>
    </div>

    <br>

    <div class="row">

        <div class="col-xs-12">
            <table class="table" style="margin-right: 3px;border:none !important;">

                @foreach($invoice->items as $item)
                    @if($item->belong_to_kit==false  && $item->show_price_in_print_mode  && $item->item != null)
                        <tbody>


                        <tr style="">

                            <td colspan="6">
                                <span>{{mb_substr($item->item->locale_name, 0,55) }}</span><br><span>{{$item->item->barcode}}</span>
                                <span class="pull-left">الكمية : {{ $item->qty }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="margin-right: -2px;"><span>الكمية</span></td>
                            @if($invoice->show_items_price_in_print_mode)
                                <td><span>السعر</span></td>
                                <td><span>المجموع</span></td>
                                <td><span>الخصم</span></td>
                                <td><span>الضريبة </span></td>
                                <td><span>النهائي</span></td>
                            @endif
                        </tr>
                        @if($invoice->show_items_price_in_print_mode)
                            <tr>
                                <td style="padding-right:10px;"><span>{{ $item->qty }}</span></td>

                                <td><span>{{ $item->price }}</span></td>
                                <td><span>{{ $item->total }}</span></td>
                                <td><span>{{ $item->discount }}</span></td>
                                {{--                            <td><span>{{ $item->subtotal }}</span></td>--}}
                                <td><span>{{ $item->tax }}</span></td>
                                <td><span>{{ $item->net }}</span></td>

                            </tr>


                        @endif
                        @foreach($item->item->serials as $index => $serial)
                            <tr>
                                <td style="padding-right:10px;text-align: right"
                                    colspan="@if($invoice->show_items_price_in_print_mode) 6 @else 2 @endif">
                                    <span>{{ $serial->serial }}</span></td>

                            </tr>
                        @endforeach

                        </tbody>


                    @endif
                @endforeach

            </table>
        </div>
    </div>
    <div class="row">


        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> الاجمالي</span>
                <span class="pull-left">  {{moneyFormatter($invoice->total) }}</span>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> الخصم</span>
                <span class="pull-left">{{moneyFormatter($invoice->discount) }}</span>
            </div>
        </div>


        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> الصافي</span>
                <span class="pull-left">  {{moneyFormatter($invoice->subtotal) }}</span>
            </div>
        </div>

        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> (15%) الضريبة  </span>
                <span class="pull-left"> {{moneyFormatter($invoice->tax) }}</span>
            </div>
        </div>


        <div class="col-xs-12">
            <div class="header_title">
                <span class="pull-right"> النهائى</span>
                <span class="pull-left"> <span> {{moneyFormatter($invoice->net )
                    }}</span></span>
            </div>
        </div>


    </div>

    <div style="border:1px solid #777"></div>
    <div class="row">
        <div class="col-xs-12">
            <div class="text-center">
                <span class="header_title total_header">{{ mb_substr($invoice->notes,0,255) }}</span>
            </div>
        </div>
    </div>

    <hr style="border:1px solid #777">
    <div class="row">
        <div class="col-md-12 text-center">
            <div id="barcode_demo" style="margin: auto;"></div>
        </div>
    </div>
    <div class="row" style="margin-top: 10px;margin-bottom: 10px;">
        <div class="col-md-12">* البضاعة المباعة لاترد ولا تستبدل بعد فتحها .</div>
        <div class="col-md-12">* الارجاع خلال ثلاثة أيام .</div>
        <div class="col-md-12">* التبديل خلال سبعة أيام .</div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="text-center">
                <span class="header_title total_header">{{ $invoice->organization->city_ar  }} - {{
                $invoice->organization->address_ar }} - {{
                $invoice->organization->phone_number }}</span>
            </div>
        </div>
        {{--        <div class="col-xs-6">--}}
        {{--            <div class="text-center">--}}

        {{--                <barcode v-bind:value="{{$invoice->title }}">--}}
        {{--                </barcode>--}}

        {{--                <show-barcode-component title="{{$invoice->title }}"></show-barcode-component>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>

    <div class="row">


        <div class="col-xs-12">
            <div align="center" style="font-size: 13px !important;"> الرقم الضريبي : {{$invoice->organization->vat}}</div>

            <p align="center" style="font-size: 13px !important;margin-top: 0px !important;"> السجل التجاري :
                {{$invoice->organization->cr
                }}</p>
        </div>
        <div class="col-xs-12">
            <div class="text-center">
                <span class="header_title total_header">سعدنا بخدمتكم</span>
            </div>
        </div>
        <div class="col-xs-12">
            <div class="text-center">
                {!! $invoice->tlvQrCode(); !!}
            </div>
        </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="{{asset('accounting/js/jquery-barcode.min.js')}}"></script>

<script>
  $('#barcode_demo').barcode(
    "{{ $invoice->invoice_number }}",// Value barcode (dependent on the type of barcode)

    'code39' // type (string)

  )

  // print();
</script>

