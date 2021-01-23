@extends('accounting.layout.master')

@section('title',__('pages/categories.create'))



@section("before_content")

@endsection




@section("content")
    <div class="panel panel-custom-form">
        <form method="post" action="{{ route('accounting.categories.store') }}" class="panel-body" enctype='multipart/form-data' >
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @error('ar_name')has-error @enderror ">
                        <input class="form-control arabic-input" name="ar_name"
                               placeholder="{{trans('pages/categories.ar_name')}}" value="{{old('ar_name')}}">
                        @error('ar_name')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @error('name')has-error @enderror">
                        <input class="form-control arabic-input" name="name"
                               placeholder="{{trans('pages/categories.name')}}" value="{{old('name')}}">
                        @error('name')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @error('ar_description')has-error @enderror">
                        <input class="form-control arabic-input" name="ar_description"
                                  placeholder="{{trans('pages/categories.ar_description')}}" value="{{old('ar_description')
                                  }}"/>
                        @error('ar_description')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @error('description')has-error @enderror">

                        <input class="form-control arabic-input" name="description"
                               placeholder="{{trans('pages/categories.description')}}" value="{{old('description')
                                  }}"/>



                        @error('description')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group @error('parent_id')has-error @enderror">
                        <select class="form-control" name="parent_id" placeholder="{{trans('pages/categories.parent_id')
                        }}">
                            <option class="form-control" value="0">{{trans('pages/categories.main_category') }}</option>
                            @foreach($categories as $category)
                                <option
                                        @if(!empty(old('parent_id')) && old('parent_id')==$category['id'] && old
                                        ('parent_id')!=0)
                                        selected
                                        @elseif($parent_id==$category['id'] && empty(old('parent_id')))
                                        selected
                                        @endif
                                        class="form-control" value="{{
                                $category['id']
                                }}">{{
                                $category['locale_name']
                            }}</option>
                            @endforeach
                        </select>
                        @error('parent_id')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>

                </div>


                {{ $errors }}
                {{-- <div class="col-md-6">
                    <div class="form-group @error('is_available_online')has-error @enderror">
                        <toggle-button
                                :value="true"
                                name="is_available_online"
                                :async="true"
                                :font-size="19" :height='35' :labels="{checked: 'متاح في الاونلاين', unchecked: 'غير متاح  '}"
                                :width='200'
                        ></toggle-button>
                        @error('is_available_online')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div> --}}
                {{-- <div class="col-md-6">
                    <div class="form-group @error('parent_id')has-error @enderror">
                        <select class="form-control" name="parent_id" placeholder="{{trans('pages/categories.parent_id')
                        }}">
                            <option class="form-control" value="0">{{trans('pages/categories.main_category') }}</option>
                            @foreach($categories as $cat)
                                <option
                                        @if(!empty(old('parent_id')) && old('parent_id')==$cat['id'])
                                        selected
                                        @elseif($category->parent_id==$cat['id'] && empty(old('parent_id')))
                                        selected
                                        @endif
                                        class="form-control" value="{{
                                $cat['id']
                                }}">{{
                                $cat['locale_name']
                            }}</option>
                            @endforeach
                        </select>
                        @error('is_available_online')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div> --}}


                <div class="col-md-2">
                    <div class="form-group @error('is_available_online')has-error @enderror">
                        <toggle-button
                                :value="true"
                                name="is_available_online"
                                :async="true"
                                :font-size="19" :height='35' :labels="{checked: 'اونلاين', unchecked: 'اوفلاين'}"
                                :width='100'
                        ></toggle-button>
                        @error('is_available_online')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>

                <div class="col-md-1">
                    <div class="form-group @error('sorting')has-error @enderror">
                       <input class="form-control arabic-input" name="sorting"
                               value="{{ old('sorting') }}"
                               placeholder="{{trans('pages/categories.sorting')}}">
                        @error('sorting')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>



                <div class="col-md-3">
                    <div class="form-group @error('image')has-error @enderror">
                       <input type='file' class="form-control arabic-input" name="image"
                               value="{{ old('image') }}"
                               placeholder="{{trans('pages/categories.image')}}">
                        @error('image')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>






            </div>

                <div class="row">
                    <div class="col-md-5 col-md-offset-1">
                        <button type="submit" class="btn btn-custom-primary">{{ trans('pages/categories.create')
                        }}</button>
                    </div>
                    <div class="col-md-2 col-md-offset-3">

                        <a href="{{ route('accounting.categories.index') }}" class="btn btn-custom-default">{{ trans
                        ('reusable.cancel')
                        }}</a>

                    </div>
                </div>
        </form>
    </div>
@endsection





@section("after_content")
@endsection