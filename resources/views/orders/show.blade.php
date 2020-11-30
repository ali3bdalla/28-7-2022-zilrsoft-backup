@extends('accounting.layout.master')

@section('title',$order->id)




@section("content")

    {{--    <div class="panel">--}}
    {{--        <div class="panel-heading">--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-md-6">--}}
    {{--                    <div class="input-group"><span id="vendors-list" class="input-group-addon">العميل</span> <input--}}
    {{--                                type="text" name="" disabled="disabled" value="{{$order->user->name}}"--}}
    {{--                                class="form-control"></div>--}}
    {{--                </div>--}}
    {{--                <div class="col-md-6">--}}
    {{--                    <div class="input-group"><span id="vendors-list" class="input-group-addon">التاريخ والوقت</span>--}}
    {{--                        <input type="text" name="" disabled="disabled" value="{{ $order->paymentDetail->created_at }}"--}}
    {{--                               class="form-control date_field_center"></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-md-6">--}}
    {{--                    <div class="input-group"><span id="vendors-list" class="input-group-addon">البنك المحول منه </span>--}}
    {{--                        <input--}}
    {{--                                type="text" name="" disabled="disabled"--}}
    {{--                                value="{{ $order->paymentDetail->senderAccount->bank->ar_name }}" class="form-control">--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--                <div class="col-md-6">--}}
    {{--                    <div class="input-group"><span id="vendors-list"--}}
    {{--                                                   class="input-group-addon">رقم الحساب المحول منه</span> <input--}}
    {{--                                type="text" name="" disabled="disabled"--}}
    {{--                                value="{{ $order->paymentDetail->senderAccount->detail }}" class="form-control"></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}

    {{--            <div class="row">--}}
    {{--                <div class="col-md-12">--}}
    {{--                    <div class="input-group"><span id="vendors-list" class="input-group-addon">البنك المحول له </span>--}}
    {{--                        <input--}}
    {{--                                type="text" name="" disabled="disabled"--}}
    {{--                                value="{{ $order->paymentDetail->receivedBank->ar_name }}" class="form-control"></div>--}}
    {{--                </div>--}}

    {{--            </div>--}}
    {{--            <div class="row">--}}
    {{--                <div class="col-md-6">--}}
    {{--                    <div class="input-group"><span id="vendors-list" class="input-group-addon"> الاسم الاول  </span>--}}
    {{--                        <input--}}
    {{--                                type="text" name="" disabled="disabled" value="{{ $order->paymentDetail->first_name }}"--}}
    {{--                                class="form-control"></div>--}}
    {{--                </div>--}}
    {{--                <div class="col-md-6">--}}
    {{--                    <div class="input-group"><span id="vendors-list" class="input-group-addon">الاسم الثاني</span>--}}
    {{--                        <input--}}
    {{--                                type="text" name="" disabled="disabled" value="{{ $order->paymentDetail->last_name }}"--}}
    {{--                                class="form-control"></div>--}}
    {{--                </div>--}}
    {{--            </div>--}}


    {{--            <div class="row">--}}
    {{--                <div class="col-md-12 text-center">--}}
    {{--                    <order-payment-options :order='@json($order)'></order-payment-options>--}}
    {{--                </div>--}}

    {{--            </div>--}}


    {{--        </div>--}}
    {{--    </div>--}}

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-primary">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draft->invoice_number}}</h4>

                    <p>عرض الطلب</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-file-alt order__panel-card-icon"></i>
                </div>
            </a>
        </div>

        @if($order->invoice_id)
            <div class="col-lg-3 col-xs-6">
                <a href="{{ route('sales.show',$order->invoice_id) }}" class="small-box bg-aqua">
                    <div class="inner">
                        <h4 style="font-weight: bolder">{{$order->invoice->invoice_number}}</h4>

                        <p>عرض الفاتورة</p>
                    </div>
                    <div class="order__panel-card-icon-container">
                        <i class="fa fa-file-alt order__panel-card-icon"></i>
                    </div>
                </a>
            </div>
        @endif


{{--        @if($order->invoice_id)--}}
            <div class="col-lg-3 col-xs-6">
                <a href="{{ route('sales.edit',$order->draft_id) }}" class="small-box bg-red">
                    <div class="inner">
                        <h4 style="font-weight: bolder">{{$order->draft->invoice_number}}</h4>

                        <p> المرتجع</p>
                    </div>
                    <div class="order__panel-card-icon-container">
                        <i class="fa fa-redo order__panel-card-icon"></i>
                    </div>
                </a>
            </div>
{{--        @endif--}}

        <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-yellow">
                <div class="inner">
                    <h4 style="font-weight: bolder">-</h4>

                    <p> مندوب التوصيل</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-shipping-fast order__panel-card-icon"></i>
                </div>
            </a>
        </div>


        <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-green">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draft->invoice_number}}</h4>

                    <p>بيانات العميل</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-user-alt order__panel-card-icon"></i>
                </div>
            </a>
        </div>


        <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-purple">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draft->invoice_number}}</h4>

                    <p> عنوان الشحن</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-address-card order__panel-card-icon"></i>
                </div>
            </a>
        </div>


        <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-orange">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draft->invoice_number}}</h4>

                    <p> تاكيد الدفع</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-check order__panel-card-icon"></i>
                </div>
            </a>
        </div>


        <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-aqua">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draft->invoice_number}}</h4>

                    <p>بيانات السداد</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-money-bill-alt order__panel-card-icon"></i>
                </div>
            </a>
        </div>


        <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-teal">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draft->invoice_number}}</h4>

                    <p>سجل الطلب</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-history order__panel-card-icon"></i>
                </div>
            </a>
        </div>



    </div>

@endsection



@section("after_content")

@endsection