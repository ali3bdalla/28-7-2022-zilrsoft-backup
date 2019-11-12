<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css" rel="stylesheet"
      id="bootstrap-css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=El+Messiri&display=swap" rel="stylesheet">
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
        border-top: 2px solid;
    }

    * {
        /*font-family: 'El Messiri', sans-serif !important;*/


    }

    .header_title span {
        font-size: 18px;
    }

    .total_header {
        font-size: 20px;
    }


</style>


<div class="" id="container">
    <div class="raw">

        <div class="col-xs-12">
            <div class="header_title">
                <h3 align="center">{{ $invoice->organization->title_ar }}</h3>
                <h4 align="center">القيمة المضافة : {{$invoice->organization->vat }}</h4>

            </div>


        </div>

    </div>

    <div class="row">
        <div class="col-sm-12">
            <h3 class="pull-right"> فاتورة</h3>
            <h3 class="pull-left"> {{$invoice->title }}</h3>
        </div>
    </div>

    {{--    <div class="row">--}}
    {{--        <div class="col-sm-12">--}}
    {{--            <h3 class="pull-right"> العميل</h3>--}}
    {{--            <h3 class="pull-left"> {{$invoice->sale->client->name }}</h3>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    {{--    <div class="row header_title">--}}
    {{--        <div class="col-sm-6"> العميل</div>--}}
    {{--        <div class="col-sm-6 text-left"> {{$invoice->sale->client->name }}</div>--}}
    {{--    </div>--}}
    {{--    --}}

    <div class="row header_title">
        <div class="col-xs-6"> العميل</div>
        <div class="col-xs-6 text-left"> {{$invoice->sale->client->name }}</div>
    </div>


    <div class="row header_title">
        <div class="col-xs-6"> التاريخ</div>
        <div class="col-xs-6 text-left"> {{$invoice->created_at }}</div>
    </div>


    {{--    <div>--}}
    {{--        <div class="header_title">--}}
    {{--            <span class="pull-right">العميل</span>--}}
    {{--            <span class="pull-left"> {{ $invoice->sale->client->name }}</span>--}}
    {{--        </div>--}}
    {{--    </div>--}}


    {{--    <div>--}}
    {{--        <div class="header_title">--}}
    {{--            <span class="pull-right"> التاريخ</span>--}}
    {{--            <span class="pull-left">{{ $invoice->created_at }}</span>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <div class="row">

        <div class="panel-body">
            <div class="">
                <table class="table">
                    <thead>
                    <tr>
                        <td><strong>الصنف</strong></td>
                        <td class="text-left"><strong>السعر</strong></td>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->items as $item)
                        <tr>
                            <td>
                                {{$item->item->ar_name}}<br>
                                {{$item->item->barcode}}
                            </td>
                            <td class="text-left">
                                الكمية : {{  $item->qty  }}<br>
                                {{money_format('%i',$item->qty * $item->price) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-2">

        </div>
        <div class="col-xs-10">

            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> الاجمالي</span>
                    <span class="pull-left">  {{money_format('%i ريال',$invoice->total) }}</span>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> الخصم</span>
                    <span class="pull-left">{{money_format('%i ريال',$invoice->discount) }}</span>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> الضريبة (5%)</span>
                    <span class="pull-left"> {{money_format('%i ريال',$invoice->tax) }}</span>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> الصافي</span>
                    <span class="pull-left">  {{money_format('%i ريال',$invoice->subtotal) }}</span>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> المدفوع</span>
                    <span class="pull-left"> <strong> {{money_format('%i ريال',$invoice->net - $invoice->remaining)
                    }}</strong></span>
                </div>
            </div>

            <div class="col-xs-12">
                <div class="header_title">
                    <span class="pull-right"> المبتقي</span>
                    <span class="pull-left">  {{money_format('%i ريال',$invoice->remaining) }}</span>
                </div>
            </div>

        </div>


    </div>

    <hr>
    <div class="row">
        <div class="col-xs-12">
            <div class="text-center">
                <span class="header_title total_header">{{ $invoice->organization->city_ar  }} - {{
                $invoice->organization->address_ar }}</span>
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
            <div class="text-center">
                <span class="header_title total_header">نراكم قريبا</span>
            </div>
        </div>
    </div>
</div>
