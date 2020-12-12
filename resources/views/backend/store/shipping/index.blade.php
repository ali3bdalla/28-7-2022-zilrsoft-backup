@extends('accounting.layout.master')

@section('title')




@section("content")

    <div class="col-xs-12">
        <div class="box">

            <!-- /.box-header -->

            @foreach($shippingMethods as $shippingMethod)
                <div class="col-lg-3 col-xs-6">
                    <a href="{{ route('store.shipping.edit',$shippingMethod->id) }}" class="small-box bg-primary">
                        <div class="inner">
                            <h4 style="font-weight: bolder">{{$shippingMethod->name}}</h4>

                            <p> {{$shippingMethod->ar_name}}</p>
{{--                            <p><span class="label label-success">Approved</span></p>--}}
                            <p> <button class="btn btn-success">تعديل</button></p>
                        </div>
                        <div class="order__panel-card-icon-container">
                            <img src="{{$shippingMethod->logo}}" style="height: 40px;object-fit: cover"/>
{{--                            <i class="fa fa-redo order__panel-card-icon"></i>--}}
                        </div>
                    </a>
                    <div class="small-box-footer text-center">


                    </div>
                </div>
{{--                <tr>--}}
{{--                    <td>{{$shippingMethod->name}}</td>--}}
{{--                    <td>{{$shippingMethod->ar_name}}</td>--}}
{{--                    <td>{{$shippingMethod->logo}}</td>--}}
{{--                    <td>{{$shippingMethod->is_active}}</td>--}}
{{--                    <td>John Doe</td>--}}
{{--                    <td>11-7-2014</td>--}}
{{--                    <td><span class="label label-success">Approved</span></td>--}}
{{--                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>--}}
{{--                </tr>--}}
        @endforeach
        <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>

@endsection



@section("after_content")

@endsection