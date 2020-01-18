@extends('accounting.layout.master')

@section('title',__('pages/accounts.creation'))



@section("before_content")

@endsection




@section("content")
    <div class="panel panel-custom-form">
        <form method="post" action="{{ route('accounting.accounts.store') }}" class="panel-body">
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
                    <div class="form-group @error('parent_id')has-error @enderror">
                        <select class="form-control" name="parent_id" placeholder="{{trans('pages/categories.parent_id')
                        }}">
                            @foreach($accounts as $category)
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
                <div class="col-md-6">
                    <div class="form-group @error('account_type')has-error @enderror">
                        <select class="form-control" name="account_type" placeholder="{{trans('pages/accounts.account_type')
                        }}">
                            <option
                                    @if(!empty(old('account_type')) && old('account_type')=='debit')
                                    selected

                                    @endif
                                    class="form-control" value="debit">مدين
                            </option>

                            <option
                                    @if(!empty(old('account_type')) && old('account_type')=='credit')
                                    selected
                                    @endif
                                    class="form-control" value="credit">دائن
                            </option>

                        </select>
                        @error('account_type')
                        <small class="text-danger">
                            {{ $message}}
                        </small>
                        @enderror

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <toggle-button

                        name="is_gateway"
                        :async="true"
                        :font-size="19" :height='30' :labels="{checked: 'خزينة', unchecked: 'حساب عادي '}"
                        :width='140'
                ></toggle-button>
            </div>

            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <button type="submit" class="btn btn-custom-primary">{{ trans('buttons.create')
                        }}</button>
                </div>
                <div class="col-md-2 col-md-offset-3">

                    <a href="{{ route('accounting.accounts.index') }}" class="btn btn-custom-default">{{ trans
                        ('reusable.back')
                        }}</a>

                </div>
            </div>

        </form>
    </div>
@endsection





@section("after_content")
@endsection