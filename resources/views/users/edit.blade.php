@extends('layouts.master2')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('management.users.store') }}">
                @csrf
                <div class="card">
                    <div class="card-header">{{ trans('users.update_user',['user'=>$user->name]) }}</div>
                    <div class="card-body">
                        <div class="form-group row text-center">
                          <!--   <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('users.email_address') }}</label> -->
                            <div class="col-md-3">
                                <input 
                                @if($user->is_client)
                                    checked="checked" 
                                @endif
                                name='is_client'
                                type="checkbox"  data-toggle="toggle" data-on="{{ trans('users.user_type',['type'=>'client,']) }}" data-off="{{ trans('users.user_type',['type'=>'client,']) }}" data-onstyle="success" data-offstyle="danger">

                            </div>


                            <div class="col-md-3">
                                <input 
                                value="true" 
                                @if($user->is_manager)
                                    checked="checked" 
                                @endif
                                name='is_manager'
                                type="checkbox"  data-toggle="toggle" data-on="{{ trans('users.user_type',['type'=>'manager,']) }}" data-off="{{ trans('users.user_type',['type'=>'manager,']) }}" data-onstyle="success" data-offstyle="danger">

                            </div>
                            <div class="col-md-3">
                                <input 
                                @if($user->is_vendor)
                                    checked="checked" 
                                @endif
                                name='is_vendor'
                                type="checkbox"  data-toggle="toggle" data-on="{{ trans('users.user_type',['type'=>'vendor,']) }}" data-off="{{ trans('users.user_type',['type'=>'vendor,']) }}" data-onstyle="success" data-offstyle="danger">

                            </div>

                            <div class="col-md-3">
                                <input 
                                value="true" 
                                @if($user->is_supplier)
                                    checked="checked" 
                                @endif
                                name='is_supplier'
                                type="checkbox"  data-toggle="toggle" data-on="{{ trans('users.user_type',['type'=>'supplier,']) }}" data-off="{{ trans('users.user_type',['type'=>'supplier,']) }}" data-onstyle="success" data-offstyle="danger">

                            </div>
                        </div>
                        <hr/>


                        <div class="form-group row text-center">
                            <label for="memebership_type" class="col-md-3 col-form-label text-md-right">{{ trans('users.memebership_type') }}</label>
                            
                            <div class="col-md-6">
                                <input 
                                checked
                                value="individual" 
                                name='memebership_type'
                                class="btn btn-block" 
                                type="checkbox"  data-toggle="toggle" data-on="{{ trans('users.user_type',['type'=>'individual']) }}" data-off="{{ trans('users.user_type',['type'=>'company,']) }}" data-onstyle="info" data-offstyle="warning">

                            </div>

                        </div>

                        <hr/>

                        <div class="form-group row">
                          <!--   <label for="email" class="col-md-4 col-form-label text-md-right">{{ trans('users.email_address') }}</label> -->
                            <div class="col-md-6">
                                <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="@empty(old('name')){{ $user->name }}@else {{ old('name') }} @endif" placeholder="{{ trans('users.name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" value="@empty(old('phone_number')){{ $user->phone_number }}@else {{ old('phone_number') }} @endif" required  placeholder="{{ trans('users.phone_number') }}" autofocus>

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        <div class="form-group row mb-0" id='user_only_submit_btn'>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('buttons.save') }}
                                </button>
                            </div>
                        </div> 
                    </div>
                </div>
                <hr/>
                
            </form>
        </div>
    </div>
</div>

@stop