@extends('accounting.layout.master')

@section('title','بيانات الشحن  #' . $order->id)




@section("content")

    
       <div class="panel">
           <div class="panel-heading">
               <div class="row">
                   <div class="col-md-6">
                       <div class="input-group"><span id="vendors-list" class="input-group-addon">اسم الاول</span> <input
                                   type="text" name="" disabled="disabled" value="{{$order->shippingAddress->first_name}}"
                                   class="form-control"></div>
                   </div>
                   <div class="col-md-6">
                    <div class="input-group"><span id="vendors-list" class="input-group-addon">اسم الاخير</span> <input
                        type="text" name="" disabled="disabled" value="{{$order->shippingAddress->last_name}}"
                        class="form-control"></div>
                   </div>
               </div>
               <div class="row">
                   <div class="col-md-6">
                       <div class="input-group"><span id="vendors-list" class="input-group-addon">المدينة </span>
                           <input
                                   type="text" name="" disabled="disabled"
                                   value="{{ $order->shippingAddress->city->name }}" class="form-control">
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="input-group"><span id="vendors-list"
                                                      class="input-group-addon">رقم الهاتف</span> <input
                                   type="text" name="" disabled="disabled"
                                   value="{{ $order->shippingAddress->phone_number }}" class="form-control"></div>
                   </div>
               </div>

               <div class="row">
                   <div class="col-md-12">
                    <div class="input-group"><span id="vendors-list"
                        class="input-group-addon"> الوصف</span> <input
     type="text" name="" disabled="disabled"
     value="{{ $order->shippingAddress->description }}" class="form-control"></div>
                   </div>
                   
                </div>
               </div>
              
               <div class="row">
                <div class="col-md-6">
                 <div class="input-group"><span id="vendors-list"
                     class="input-group-addon"> وسيلة الشحن</span> <input
  type="text" name="" disabled="disabled"
  value="{{ $order->shippingMethod->locale_name }}" class="form-control"></div>
                </div>
                <div class="col-md-6">
                    <div class="input-group"><span id="vendors-list"
                        class="input-group-addon"> مندوب التوصيل</span> <input
     type="text" name="" disabled="disabled"
     value="{{ $order->deliveryMan ? $order->deliveryMan->locale_name : "" }}" class="form-control"></div>
                   </div>
             </div>
            </div>

               @if($order->status == 'in_progress' && !$order->deliveryMan)
               <div class="row" >
                   <div class="col-md-12 text-center">
                       <order-shipping-options :order='@json($order)'></order-shipping-options>
                   </div>

               </div>
               @endif


           </div>
       </div>
@endsection



@section("after_content")

@endsection