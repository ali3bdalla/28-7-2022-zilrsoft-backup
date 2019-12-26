@extends('accounting.layout.master')

@section('title',__('pages/categories.create'))



@section("before_content")

@endsection




@section("content")
    <div class="panel panel-custom-form">
        <form method="post" action="{{ route('accounting.categories.store') }}" class="panel-body">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group @error('ar_name')has-error @enderror ">
                        <input class="form-control arabic-input" name="ar_name"
                               placeholder="{{trans('pages/categories.ar_name')}}" value="{{old('ar_name') != ""
                               ? old('ar_name') : $category->ar_name}}">
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
                               placeholder="{{trans('pages/categories.name')}}" value="{{old('name') != "" ?
                               old('name') : $category->name}}">
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
                        <textarea class="form-control" name="ar_description"
                                  placeholder="{{trans('pages/categories.ar_description')}}">{{old('ar_description') !=
                                   "" ? old('ar_description') : $category->ar_description}}</textarea>
                        @error('ar_description')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group @error('description')has-error @enderror">
                        <textarea class="form-control" name="description" placeholder="{{trans('pages/categories.description')
                        }}">{{old('description') != "" ? old('description') : $category->description}}</textarea>
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
                            @foreach($categories as $tempCategory)
                                <option
                                        @if(!empty(old('parent_id')) && old('parent_id')==$tempCategory['id'] && old
                                        ('parent_id')!=0)
                                        selected
                                        @elseif($category->parent_id==$tempCategory['id'] && empty(old('parent_id')))
                                        selected
                                        @endif
                                        class="form-control" value="{{
                                $tempCategory['id']
                                }}">{{
                                $tempCategory['locale_name']
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
            </div>
        </form>
    </div>
@endsection





@section("after_content")
@endsection