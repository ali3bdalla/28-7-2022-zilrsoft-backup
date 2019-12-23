@extends('accounting.layout.master')

@section('title',__('pages/branches.edit') . " | " . $branch->locale_name)



@section("before_content")

@endsection




@section("content")
    <div class="panel panel-custom-form">
        <form method="post" action="{{ route('accounting.branches.update',$branch->id) }}" class="panel-body">
            @csrf
            @method('patch')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @error('ar_name')has-error @enderror ">
                        <input class="form-control arabic-input" name="ar_name"
                               placeholder="{{trans('pages/branches.ar_name')}}" value="{{ empty(old('ar_name')) ?
                               $branch->ar_name : old('ar_name')}}">
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
                               placeholder="{{trans('pages/branches.name')}}" value="{{ empty(old('name')) ?
                               $branch->name : old('name')}}">
                        @error('name')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group @error('phone_number')has-error @enderror">
                        <input class="form-control arabic-input" name="phone_number"
                               placeholder="{{trans('pages/branches.phone_number')}}" value="{{ empty(old('phone_number')) ?
                               $branch->phone_number : old('phone_number')}}">
                        @error('phone_number')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
            </div>




            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <button type="submit" class="btn btn-custom-primary">{{ trans('pages/branches.edit')
                        }}</button>
                </div>
                <div class="col-md-2 col-md-offset-3">

                    <a href="{{ route('accounting.branches.index') }}"
                       class="btn btn-custom-default">{{ __('reusable.cancel')}}</a>

                </div>
            </div>
        </form>
    </div>
@endsection





@section("after_content")
@endsection