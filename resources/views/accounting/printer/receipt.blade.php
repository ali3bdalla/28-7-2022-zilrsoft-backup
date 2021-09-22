<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset("css/static/invoice-receipt.css") }}" rel="stylesheet" type="text/css"></link>
    <title>{{ $invoice->invoice_number }}</title>
</head>
<body>
<div class="content">
    <div class="organization_name">
        <div>{{$invoice->organization->title_ar }}</div>
    </div>
    <div class="attribute-raw">
        <div class="attribute-title">
            رقم الفاتورة
        </div>
        <div class="attribute-value">
            {{$invoice->invoice_number }}
        </div>
    </div>
    <div class="attribute-raw">
        <div class="attribute-title">
            اسم العميل
        </div>
        <div class="attribute-value">
            {{$invoice->user_name }}
        </div>
    </div>
    <div class="attribute-raw">
        <div class="attribute-title">
            التاريخ
        </div>
        <div class="attribute-value">
            {{$invoice->created_at }}
        </div>
    </div>
    <div class="attribute-raw">
        <div class="attribute-title">
            البائع
        </div>
        <div class="attribute-value">
            {{$invoice->manager->locale_name  }}
        </div>
    </div>
    <table class="items-table">
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
                        <td><span>{{ $item->tax }}</span></td>
                        <td><span>{{ $item->net }}</span></td>
                    </tr>
                @endif
                @foreach($item->item->serials as $index => $serial )
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
            <div align="center" style="font-size: 13px !important;"> الرقم الضريبي : {{$invoice->organization->vat
                }}</div>

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
                {!! QrCode::size(100)->generate(route('accounting.public-invoice.show',$invoice->getEncryptedPublicId())); !!}
            </div>
        </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="{{asset('accounting/js/jquery-barcode.min.js')}}"></script>

<script>
  $('#barcode_demo').barcode(
    "{{ $invoice->invoice_number }}",
    'code39'
  )
</script>


</body>
</html>
