@extends('accounting.layout.master')

@section('title',$order->id)




@section("content")


    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-primary">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draftInvoice->invoice_number}}</h4>
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


       @if($order->invoice_id)
            <div class="col-lg-3 col-xs-6">
                <a href="{{ route('sales.edit',$order->draft_id) }}" class="small-box bg-red">
                    <div class="inner">
                        <h4 style="font-weight: bolder">{{$order->draftInvoice->invoice_number}}</h4>

                        <p> المرتجع</p>
                    </div>
                    <div class="order__panel-card-icon-container">
                        <i class="fa fa-redo order__panel-card-icon"></i>
                    </div>
                </a>
            </div>
       @endif
{{-- 
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
        </div> --}}

{{-- 
        <div class="col-lg-3 col-xs-6">
            <a href="/store/orders/{{$order->id}}/customer-data" class="small-box bg-green">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draftInvoice->invoice_number}}</h4>

                    <p>بيانات العميل</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-user-alt order__panel-card-icon"></i>
                </div>
            </a>
        </div> --}}


        <div class="col-lg-3 col-xs-6">
            <a href="/store/orders/{{$order->id}}/view-shipping" class="small-box bg-purple">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draftInvoice->invoice_number}}</h4>

                    <p> تفاصيل الشحن</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-address-card order__panel-card-icon"></i>
                </div>
            </a>
        </div>


        <div class="col-lg-3 col-xs-6">
            <a href="/store/shipping/{{$order->shipping_method_id}}/{{ $order->id }}/create-order-transaction" class="small-box bg-purple">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draftInvoice->invoice_number}}</h4>

                    <p> تفاصيل الشحن</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-address-card order__panel-card-icon"></i>
                </div>
            </a>
        </div>


        <div class="col-lg-3 col-xs-6">
            <a href="/store/orders/{{$order->id}}/view-payment" class="small-box bg-orange">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draftInvoice->invoice_number}}</h4>

                    <p> تاكيد الدفع</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-check order__panel-card-icon"></i>
                </div>
            </a>
        </div>


       @if(!$order->paymentDetail)
             <div class="col-lg-3 col-xs-6">
            <a href="{{ $order->generatePayOrderUrl() }}" class="small-box bg-aqua">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draftInvoice->invoice_number}}</h4>

                    <p>رابط السداد</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-money-bill-alt order__panel-card-icon"></i>
                </div>
            </a>
        </div>
       @endif


        {{-- <div class="col-lg-3 col-xs-6">
            <a href="{{ route('sales.show',$order->draft_id) }}" class="small-box bg-teal">
                <div class="inner">
                    <h4 style="font-weight: bolder">{{$order->draftInvoice->invoice_number}}</h4>

                    <p>سجل الطلب</p>
                </div>
                <div class="order__panel-card-icon-container">
                    <i class="fa fa-history order__panel-card-icon"></i>
                </div>
            </a>
        </div> --}}



    </div>

@endsection



@section("after_content")

@endsection