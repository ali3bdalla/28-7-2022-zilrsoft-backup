@extends('accounting.layout.master')

@section('title',__('pages/filters.create'))



@section("before_content")

@endsection




@section("content")
    <div class="panel panel-custom-form">
        <form method="post" action="{{ route('api.filters.store') }}" class="panel-body">
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
                <div class="col-md-5 col-md-offset-1">
                    <button type="submit" class="btn btn-custom-primary">{{ trans('pages/filters.create')
                        }}</button>
                </div>
                <div class="col-md-2 col-md-offset-3">

                    <a href="{{ route('accounting.filters.index') }}" class="btn btn-custom-default">{{ trans
                        ('reusable.cancel')
                        }}</a>

                </div>
            </div>
        </form>
    </div>
@endsection





@section("after_content")
@endsection