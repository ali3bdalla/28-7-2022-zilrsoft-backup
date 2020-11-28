@extends('accounting.layout.master')

@section('title',$order->id)




@section("content")
    <div class="panel">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group"><span id="vendors-list" class="input-group-addon">العميل</span> <input
                                type="text" name="" disabled="disabled" value="{{$order->user->name}}"
                                class="form-control"></div>
                </div>
                <div class="col-md-6">
                    <div class="input-group"><span id="vendors-list" class="input-group-addon">التاريخ والوقت</span>
                        <input type="text" name="" disabled="disabled" value="{{ $order->paymentDetail->created_at }}"
                               class="form-control date_field_center"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group"><span id="vendors-list" class="input-group-addon">البنك المحول منه </span>
                        <input
                                type="text" name="" disabled="disabled"
                                value="{{ $order->paymentDetail->senderAccount->bank->ar_name }}" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-group"><span id="vendors-list"
                                                   class="input-group-addon">رقم الحساب المحول منه</span> <input
                                type="text" name="" disabled="disabled"
                                value="{{ $order->paymentDetail->senderAccount->detail }}" class="form-control"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="input-group"><span id="vendors-list" class="input-group-addon">البنك المحول له </span>
                        <input
                                type="text" name="" disabled="disabled"
                                value="{{ $order->paymentDetail->receivedBank->ar_name }}" class="form-control"></div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group"><span id="vendors-list" class="input-group-addon"> الاسم الاول  </span>
                        <input
                                type="text" name="" disabled="disabled" value="{{ $order->paymentDetail->first_name }}"
                                class="form-control"></div>
                </div>
                <div class="col-md-6">
                    <div class="input-group"><span id="vendors-list" class="input-group-addon">الاسم الثاني</span>
                        <input
                                type="text" name="" disabled="disabled" value="{{ $order->paymentDetail->last_name }}"
                                class="form-control"></div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 text-center">
                    <order-payment-options :order='@json($order)'></order-payment-options>
                </div>

            </div>


        </div>
    </div>
@endsection



@section("after_content")

@endsection