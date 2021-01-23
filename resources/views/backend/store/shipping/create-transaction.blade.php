@extends('accounting.layout.master')

@section('title')




@section('content')



    <div class="box box-primary">
        <div class="box-header">
            <h3 class="box-title">انشاء بوليصة</h3>
        </div>
        <form action="{{ route('store.shipping.store_transaction', $shipping->id) }}" method="post">
            @csrf

            @include('backend.store.shipping.sender_details')
            <div class="box-body">
                <h3 class="mb-3">بيانات المستقبل</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>الاسم الاول</label>
                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" />
                            @error('first_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>الاسم الثاني</label>
                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" />

                            @error('last_name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>المدينة</label>
                            <select class="form-control" name="city_id">
                                @foreach ($citites as $city)
                                    <option value="{{ $city->id }}">{{ $city->locale_name }}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>رقم الجوال </label>
                            <input type="text" class="form-control" name="phone_number" value="{{ old('phone_number') }}" />
                            @error('phone_number')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>العنوان</label>
                            <textarea type="text" class="form-control" name="address"
                                value="{{ old('address') }}"></textarea>
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                </div>



                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>الرقم المرجعي</label>
                            <input type="text" class="form-control" name="reference" value="{{ old('reference') }}" />
                            @error('reference')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>الوصف</label>
                            <input type="text" class="form-control" name="description" value="{{ old('description') }}" />
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>الدفع عند الاستلام</label>
                            <input type="text" class="form-control" name="cod" value="{{ old('cod') }}" />
                            @error('cod')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>



                    <div class="col-md-2">
                        <div class="form-group">
                            <label> عدد الصناديق </label>
                            <input type="text" class="form-control" name="boxes" value="{{ old('boxes') }}" />
                            @error('boxes')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="col-md-2">
                        <div class="form-group">
                            <label> الوزن </label>
                            <input type="text" class="form-control" name="weight" value="{{ old('weight') }}" />
                            @error('weight')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <button class="btn btn-primary" type="submit">انشاء</button>

            </div>


        </form>
    </div>
    <!-- /.box -->




@endsection



@section('after_content')

@endsection
