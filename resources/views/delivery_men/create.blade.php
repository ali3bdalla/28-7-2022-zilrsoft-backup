@extends('accounting.layout.master')

@section('title',"اضافة مندوب توصيل")



@section("content")
    <div class="panel panel-custom-form">
        <form method="post" action="{{ route('delivery_men.store') }}" class="panel-body">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @error('first_name')has-error @enderror ">
                        <input class="form-control arabic-input" name="first_name"
                               placeholder="الاسم الاول" value="{{old('first_name')}}">
                        @error('first_name')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @error('last_name')has-error @enderror">
                        <input class="form-control arabic-input" name="last_name"
                               placeholder="الاسم الثاني" value="{{old('last_name')}}">
                        @error('last_name')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @error('id_number')has-error @enderror">
                        <input class="form-control arabic-input" name="id_number"
                               placeholder="رقم الهوية" value="{{old('id_number')
                                  }}"/>
                        @error('id_number')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @error('phone_number')has-error @enderror">

                        <input class="form-control arabic-input" name="phone_number"
                               placeholder="رقم الجوال" value="{{old('phone_number')
                                  }}"/>


                        @error('phone_number')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @error('city_id')has-error @enderror">

                        {{--                        <input class="form-control arabic-input" name="city_id"--}}
                        {{--                               placeholder=" المدينة" value="{{old('city_id')--}}
                        {{--                                  }}"/>--}}


                        <select class="form-control" name="city_id" placeholder="المدينة">
                            @foreach($cities as $city)
                                <option
                                        @if(!empty(old('city_id')) === $city['id'])
                                        selected

                                        @endif
                                        class="form-control" value="{{$city['id'] }}">{{ $city['name']}}</option>
                            @endforeach
                        </select>


                        @error('city_id')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>


            </div>

            {{--            <div class="row">--}}
            {{--                <div class="col-md-6">--}}
            {{--                    <div class="form-group @error('parent_id')has-error @enderror">--}}
            {{--                        <select class="form-control" name="parent_id" placeholder="{{trans('pages/categories.parent_id')--}}
            {{--                        }}">--}}
            {{--                            <option class="form-control" value="0">{{trans('pages/categories.main_category') }}</option>--}}
            {{--                            @foreach($categories as $category)--}}
            {{--                                <option--}}
            {{--                                        @if(!empty(old('parent_id')) && old('parent_id')==$category['id'] && old--}}
            {{--                                        ('parent_id')!=0)--}}
            {{--                                        selected--}}
            {{--                                        @elseif($parent_id==$category['id'] && empty(old('parent_id')))--}}
            {{--                                        selected--}}
            {{--                                        @endif--}}
            {{--                                        class="form-control" value="{{--}}
            {{--                                $category['id']--}}
            {{--                                }}">{{--}}
            {{--                                $category['locale_name']--}}
            {{--                            }}</option>--}}
            {{--                            @endforeach--}}
            {{--                        </select>--}}
            {{--                        @error('parent_id')--}}
            {{--                        <small class="text-danger">--}}
            {{--                            {{ $message}}--}}
            {{--                        </small>--}}
            {{--                        @enderror--}}

            {{--                    </div>--}}

            {{--                </div>--}}

            {{--                <div class="col-md-6">--}}
            {{--                    <div class="form-group @error('is_available_online')has-error @enderror">--}}
            {{--                        <toggle-button--}}
            {{--                                :value="true"--}}
            {{--                                name="is_available_online"--}}
            {{--                                :async="true"--}}
            {{--                                :font-size="19" :height='35' :labels="{checked: 'متاح في الاونلاين', unchecked: 'غير متاح  '}"--}}
            {{--                                :width='200'--}}
            {{--                        ></toggle-button>--}}
            {{--                        @error('is_available_online')--}}
            {{--                        <small class="text-danger">--}}
            {{--                            {{ $message}}--}}
            {{--                        </small>--}}
            {{--                        @enderror--}}

            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <button type="submit" class="btn btn-custom-primary">حفظ</button>
                </div>
                <div class="col-md-2 col-md-offset-3">

                </div>
            </div>
        </form>
    </div>
@endsection



@section("after_content")
@endsection