@extends('accounting.layout.master')

@section('title')




@section("content")

    <div class="box">


        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">تعديل بيانات </h3>
            </div>
            <form role="form" method="post" action="{{route('store.shipping.update',$shipping->id)}}">
                @method('PATCH')
                <div class="box-body">
                    <div class="raw">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>الاسم بالعربي </label>
                                <input type="text" class="form-control" placeholder="اسم وسيلة الشحن عربي"
                                       name='ar_name' value="{{ $shipping->ar_name }}">
                                @error('ar_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>الاسم بالانجليزي </label>
                                <input type="text" class="form-control" placeholder="اسم وسيلة الشحن انجليزي"
                                       name='name' value="{{ $shipping->name }}">
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="raw">
                        <div class="col-md-12">
                            <label> الوزن الاساسي للشحن (كجم)</label>
                            <input type="text" class="form-control" placeholder="الوزن الاساسي"
                                   name='max_base_weight' value="{{ $shipping->max_base_weight }}">
                            @error('max_base_weight')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>


                    </div>


                    <div class="raw">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label> تكلفة الوزن الاساسي </label>
                                <input type="text" class="form-control" placeholder="اسم وسيلة الشحن عربي"
                                       name='max_base_weight_cost' value="{{ $shipping->max_base_weight_cost }}">
                                @error('max_base_weight_cost')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label> سعر بيع الوزن الاساسي </label>
                                <input type="text" class="form-control" placeholder="اسم وسيلة الشحن انجليزي"
                                       name='max_base_weight_price' value="{{ $shipping->max_base_weight_price }}">
                                @error('max_base_weight_price')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="raw">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>تكلفة الكيلو بعد الوزن الاساسي </label>
                                <input type="text" class="form-control" placeholder="تكلفة الكيلو بعد الوزن الاساسي"
                                       name='kg_after_max_weight_cost'
                                       value="{{ $shipping->kg_after_max_weight_cost }}">
                                @error('kg_after_max_weight_cost')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>سعر الكيلو بعد الوزن الاساسي</label>
                                <input type="text" class="form-control" placeholder="سعر الكيلو بعد الوزن الاساسي"
                                       name='kg_rate_after_max_price' value="{{ $shipping->kg_rate_after_max_price }}">
                                @error('kg_rate_after_max_price')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="raw">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>المنتج</label>
                                <select class="form-control" name="item_id">
                                    @foreach($expenses as $expense)
                                        <option value="{{$expense['id']}}"
                                                @if($shipping->item_id === $expense->id)
                                                selected
                                                @endif
                                        >{{$expense->locale_name}}</option>
                                    @endforeach
                                </select>
                                @error('item_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>


                    </div>


                    <div class="raw">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>المنتج</label>
                                <label for="">الشعار</label>
                                <input type="file" id="exampleInputFile">
                                @error('item_id')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="col-md-6">
                            <img src="{{$shipping->logo}}" style="height: 50px"/>

                        </div>

                    </div>


                    <div class="raw">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>المدن</label>
                                <select class="form-control" name="cities[]" multiple="">
                                    {{$citiesList}}
                                    @foreach($citiesList as $city)
                                        <option value="{{$city['id']}}"
                                                @if(in_array($city->id,$shippingCities))
                                                selected
                                                @endif
                                        >{{$city->locale_name}}</option>
                                    @endforeach
                                </select>
                                @error('cities')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>


                    </div>


                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                </div>
            </form>


            {{--            <div class="box">--}}

            {{--                <table class="data__table" width="100%">--}}
            {{--                    <thead>--}}
            {{--                    <tr class="data__table__header">--}}
            {{--                        <th class="data__table__title">اسم المندوب</th>--}}
            {{--                        <th class="data__table__title">رقم الهوية</th>--}}
            {{--                        <th class="data__table__title">رقم الجوال</th>--}}
            {{--                        <th class="data__table__title">رابط التاكيد</th>--}}
            {{--                        <th class="data__table__title">تعديل</th>--}}
            {{--                    </tr>--}}
            {{--                    </thead>--}}
            {{--                    <tbody>--}}
            {{--                    <tr class="data__table__row">--}}

            {{--                        <td class="data__table__cell"></td>--}}

            {{--                    </tr>--}}
            {{--                    </tbody>--}}

            {{--                </table>--}}
            {{--            </div>--}}
        </div>
    </div>

    <div class="box">


        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> اضافة مندوب </h3>
            </div>
            <form role="form" method="post" action="{{route('store.shipping.delivery_men.store',$shipping->id)}}">
                <div class="box-body">
                    <div class="raw">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>الاسم االاول </label>
                                <input type="text" class="form-control" placeholder="الاسم االاول"
                                       name='first_name' value="{{ old('first_name') }}">
                                @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>الاسم الاخير </label>
                                <input type="text" class="form-control" placeholder="الاسم الاخير"
                                       name='last_name' value="{{ old('last_name') }}">
                                @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                    <div class="raw">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>رقم الهوية </label>
                                <input type="text" class="form-control" placeholder=" رقم الهوية"
                                       name='id_number' value="{{ old('id_number') }}">
                                @error('id_number')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>رقم الهاتف </label>
                                <input type="text" class="form-control" placeholder="رقم الهاتف"
                                       name='phone_number' value="{{ old('phone_number') }}">
                                @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                </div>
            </form>


        </div>
    </div>

    <div class="box">
        <div class="box">

            <table class="data__table" width="100%">
                <thead>
                <tr class="data__table__header">
                    <th class="data__table__title">اسم الاول</th>
                    <th class="data__table__title">اسم الثاني</th>
                    <th class="data__table__title">رقم الهوية</th>
                    <th class="data__table__title">رقم الجوال</th>
                    <th class="data__table__title">رابط التاكيد</th>
                    <th class="data__table__title">تعديل</th>
                </tr>
                </thead>
                <tbody>
                @foreach($deliveryMen as $deliveryMan)
                    <tr class="data__table__row">

                        <form method="POST"
                              action="{{ route('store.shipping.delivery_men.update',[$shipping->id,$deliveryMan->id]) }}">
                            @method('PATCH')
                            <td class="data__table__cell">
                                <input type="text" class="form-control" value="{{$deliveryMan->first_name}}"
                                       name="update_first_name[{{$loop->index}}]"/>
                                @error('update_first_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td class="data__table__cell">
                                <input type="text" class="form-control" value="{{$deliveryMan->last_name}}"
                                       name="update_last_name[{{$loop->index}}]"/>
                                @error('update_last_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>
                            <td class="data__table__cell">
                                <input type="text" class="form-control" value="{{$deliveryMan->id_number}}"
                                       name="update_id_number[{{$loop->index}}]"/>
                                @error('update_id_number')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>

                            <td class="data__table__cell">
                                <input type="text" class="form-control" value="{{$deliveryMan->phone_number}}"
                                       name="update_phone_number[{{$loop->index}}]"/>
                                @error('update_phone_number')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </td>

                            <td class="data__table__cell"><a href="/delivery_man/confirm/{{$deliveryMan->hash}}"
                                                             target="_blank">الرابط</a>
                            </td>
                            <td class="data__table__cell">
                                <input class="btn btn-primary" type="submit" value="تعديل"/>
                            </td>
                        </form>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

@endsection



@section("after_content")

@endsection