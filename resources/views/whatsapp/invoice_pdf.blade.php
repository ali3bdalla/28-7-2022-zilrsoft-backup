<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$order->id}}</title>

    <style>
        /*@font-face {*/
        /*    font-family: myFirstFont;*/
        /*    src: url('/fonts/Zar/XB ZarBd.ttf');*/
        /*}*/

        /** {*/
        /*    font-family: myFirstFont !important;*/

        /*}*/
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 5px;
            /*border: 1px solid #eee;*/
            /*box-shadow: 0 0 10px rgba(0, 0, 0, .15);*/
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .bold-font {
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:first-child {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            /*text-align: left;*/
            padding-right: 200px;
        }
    </style>
</head>

<body>
<div class="invoice-box rtl">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="3">
                <table>
                    <tr>
                        <td class="title">
                            <img src="https://zilrsoft.com/images/logo_ar.png" style="width:100%; max-width:120px;object-fit: contain">
                        </td>
                        <td></td>

                        <td class="" style="padding-right: 20px">
                            <span style="font-weight: bold !important; font-size: 22px " class="bold-font"> رقم الفاتورة # {{$invoice->invoice_number}}</span><br>
                            التاريخ والوقت: {{$order->created_at}}<br>

                        </td>

                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="3">
                <table>
                    <tr>
                        <td>
                            فرع مؤسسة بيت المسبار التجارية<br>
                            السجل التجاري: 1132002748<br>
                            الرقم الضريبي: 301032266600003<br>
                            رقم الهاتف: 0163394000<br>
                            مبيعات الاونلاين
                        </td>
                        <td>
                            الاسم: {{$order->user->locale_name}}<br>
                            رقم الجوال: 0{{$order->user->phone_number}}<br>
                            {{$order->shippingAddress->city->locale_name}} - {{$order->shippingAddress->description}}<br>
                            طريقة التسليم: {{$order->shippingMethod->locale_name}}<br>
                            وسيلة السداد: تحويل بنكي
                        </td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td  >
                الصنف
            </td>

            <td width="100px">
                الكمية
            </td>
            <td  width="20%" style="text-align: center">
                المجموع
            </td>


        </tr>

        @foreach($invoice->items as $item)
        <tr class="item" >
            <td >
                {{$item->item->locale_name}}
            </td>

            <td  style="text-align: center">
                {{$item->qty}}
            </td>
            <td  style="text-align: center">
                {{moneyFormatter($item->net)}} {{__('store.products.sar')}}
            </td>
        </tr>
        @endforeach

        <tr class="heading" style="margin-top: 10px">
            <td>
{{--                Hosting (3 months)--}}
            </td>

            <td>
{{--                $75.00--}}
            </td>
            <td>
{{--                $75.00--}}
            </td>
        </tr>

{{--        <tr class="item last">--}}
{{--            <td>--}}
{{--                Domain name (1 year)--}}
{{--            </td>--}}

{{--            <td>--}}
{{--                $10.00--}}
{{--            </td>--}}
{{--            <td>--}}
{{--                $10.00--}}
{{--            </td>--}}
{{--        </tr>--}}

        <tr class="total">


            <td class="bold-font">
                المجموع :  {{ moneyFormatter($invoice->net) }} {{__('store.products.sar')}}
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr class="total" class="bold-font">

            <td width="230px" class="bold-font">
                الشحن :  {{ moneyFormatter($order->shipping_amount) }} {{__('store.products.sar')}}
            </td>
            <td></td>
            <td></td>

        </tr>
        <tr class="total">

            <td width="230px" class="bold-font">
                النهائى :  {{ moneyFormatter($invoice->net + $order->shipping_amount) }} {{__('store.products.sar')}}
            </td>
            <td></td>
            <td></td>

        </tr><tr class="total">

            <td width="230px" class="bold-font">
                النهائي يتضمن الضريبة (15%) : {{ moneyFormatter(((float)$invoice->net + (float)$order->shipping_amount) / 100 * 15 )  }} {{__('store.products.sar')}}
            </td>
            <td></td>
            <td></td>

        </tr>

    </table>
</div>
</body>
</html>
