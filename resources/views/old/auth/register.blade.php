@extends('old.layouts.app')

@section('content')
    <div class="container">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('management.register') }}">
            @csrf
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">{{ trans('dashboard.organization_details') }}</div>

                        <div class="card-body">
                            <div class="form-group row">



                                <div class="col-md-6">
                                    <input id="org_title_ar" type="text" class="form-control @error('org_title_ar') is-invalid
@enderror" name="org_title_ar" value="{{ old('org_title_ar') }}" required autocomplete="org_title_ar" autofocus
                                           placeholder="{{ trans('dashboard.organization_title_ar') }}"
                                    >

                                    @error('org_title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>



                                <div class="col-md-6">
                                    <input id="org_title" type="text"
                                           class="form-control @error('org_title') is-invalid @enderror"
                                           name="org_title" value="{{ old('org_title') }}" required
                                           autocomplete="org_title" autofocus
                                           placeholder="{{ trans('dashboard.organization_title_en') }}"
                                    >

                                    @error('org_title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>


                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="org_vat" type="text"
                                           class="form-control @error('org_vat') is-invalid @enderror" name="org_vat"
                                           value="{{ old('org_vat') }}" required autocomplete="org_vat"
                                           placeholder="{{ trans('dashboard.organization_vat') }}"
                                    >

                                    @error('org_vat')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <input id="org_cr" type="text"
                                           class="form-control @error('org_cr') is-invalid @enderror" name="org_cr"
                                           value="{{ old('org_cr') }}" required autocomplete="org_cr"
                                           placeholder="{{ trans('dashboard.organization_cr') }}"
                                    >

                                    @error('org_cr')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>

                            <div class="form-group row">


                                <div class="col-md-6">
                                    <select id="org_country_id" type="text"
                                            class="form-control @error('org_country_id') is-invalid @enderror select2"
                                            name="org_country_id" required
                                    >
                                        <option class="">{{ trans('dashboard.organization_country') }}</option>
                                        @isset($countries)
                                            @foreach($countries as $country)
                                                <option @if(old('org_country_id')==$country->id) selected @endif
                                                class="" value="{{ $country->id }}">{{ $country->ar_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>

                                    @error('org_country_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>




                                <div class="col-md-6">
                                    <input id="org_phone_number" type="text" class="form-control @error('org_phone_number')
                                            is-invalid
@enderror"
                                           name="org_phone_number" value="{{ old('org_phone_number') }}" required
                                           autocomplete="org_phone_number"
                                           placeholder="{{ trans('dashboard.phone_number') }}"
                                    >

                                    @error('org_phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>




                            </div>


                            <div class="form-group row">


                                <div class="col-md-6">
                                    <input id="org_city_ar" type="text" class="form-control @error('org_city_ar') is-invalid
@enderror"
                                           name="org_city_ar" value="{{ old('org_city_ar') }}" required
                                           autocomplete="org_city_ar"
                                           placeholder="{{ trans('dashboard.org_city_ar') }}"
                                    >

                                    @error('org_city_ar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <input id="org_city" type="text" class="form-control @error('org_city') is-invalid
@enderror"
                                           name="org_city" value="{{ old('org_city') }}" required
                                           autocomplete="org_city"
                                           placeholder="{{ trans('dashboard.org_city') }}"
                                    >

                                    @error('org_city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>


                            <div class="form-group row">
                                <div class="col-md-6">
                                    <input id="org_address_ar" type="text" class="form-control @error('org_address_ar')
                                            is-invalid
@enderror"
                                           name="org_address_ar" value="{{ old('org_address_ar') }}" required
                                           autocomplete="org_address_ar"
                                           placeholder="{{ trans('dashboard.address_ar') }}"
                                    >

                                    @error('org_address_ar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control @error('org_address') is-invalid
@enderror"
                                           name="org_address" value="{{ old('org_address') }}" required
                                           autocomplete="org_address"
                                           placeholder="{{ trans('dashboard.address') }}"
                                    >

                                    @error('org_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>


                            <div class="form-group row">

                                <div class="col-md-6">
                                    <select id="org_business_type" type="text"
                                            class="form-control @error('org_business_type') is-invalid @enderror select2"
                                            name="org_business_type" required
                                    >
                                        <option class="">{{ trans('dashboard.org_business_type') }}</option>
                                        @isset($types)
                                            @foreach($types as $type)
                                                <option selected
                                                         class=""
                                                        value="{{ $type->id }}">{{ $type->ar_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>

                                    @error('org_business_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                    <select id="org_type" type="text"
                                            class="form-control @error('org_type') is-invalid @enderror select2"
                                            name="org_type" required
                                    >
                                        <option class="">{{ trans('dashboard.org_type') }}</option>

                                        @foreach(config('data.types') as $key => $value)
                                            <option @if(old('org_type')==$key) selected
                                                    @endif  value="{{ $key }}">{{ $value[app()->getLocale()] }}</option>
                                        @endforeach


                                    </select>

                                    @error('org_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>


                            <div class="form-group row">

                                <label for="name"
                                       class="col-md-6 col-form-label text-md-left">{{ trans('dashboard.org_logo') }}</label>
                                <div class="col-md-6">
                                    <input id="org_logo" type="file"
                                           class="form-control @error('org_logo') is-invalid @enderror" name="org_logo"
                                           value="{{ old('org_logo') }}"
                                    >

                                    @error('org_logo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>


                            <div class="form-group row">
                                <div class="col-md-6">
                                <textarea id="org_description" type="text"
                                          class="form-control @error('org_description_ar') is-invalid @enderror"
                                          name="org_description_ar" value="{{ old('org_description_ar') }}"
                                          autocomplete="org_description_ar"
                                          placeholder="{{ trans('dashboard.org_description_ar') }}"
                                >{{ old('org_description_ar') }}</textarea>

                                    @error('org_description_ar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                                <div class="col-md-6">
                                <textarea id="org_description" type="text"
                                          class="form-control @error('org_description') is-invalid @enderror"
                                          name="org_description" value="{{ old('org_description') }}"
                                          autocomplete="org_description"
                                          placeholder="{{ trans('dashboard.org_description') }}"
                                >{{ old('org_description') }}</textarea>

                                    @error('org_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>


                        </div><!--body-->
                    </div><!--card-->

                </div><!--col-6-->

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">{{ trans('dashboard.supervisor_details') }}</div>

                        <div class="card-body">
                            <div class="form-group row">
{{--                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>--}}

                                <div class="col-md-6">
                                    <input id="name_ar" type="text"
                                           placeholder="{{ __('pages/users.name_ar')}}"
                                           class="form-control @error('name_ar') is-invalid @enderror" name="name_ar"
                                           value="{{ old('name_ar') }}" required autocomplete="name_ar" autofocus>

                                    @error('name_ar')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>



                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           placeholder="{{ __('pages/users.name')}}"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>


                            </div>

                            <div class="form-group row">
{{--                                <label for="email"--}}
{{--                                       class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

                                <div class="col-md-12">
                                    <input id="email" type="email"
                                           placeholder="{{ __('pages/users.email_address')}}"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
{{--                                <label for="password"--}}
{{--                                       class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

                                <div class="col-md-12">
                                    <input id="password" type="password"
                                           placeholder="{{ __('pages/users.password')}}"
                                           class="form-control @error('password') is-invalid @enderror" name="password"
                                           required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
{{--                                <label for="password-confirm"--}}
{{--                                       class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>--}}

                                <div class="col-md-12">
                                    <input id="password-confirm" type="password" class="form-control"
                                           placeholder="{{ __('pages/users.confirm_password')}}"
                                           name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>


                            <div class="form-group row">
{{--                                <label for="phone_number"--}}
{{--                                       class="col-md-4 col-form-label text-md-right">{{ __('pages/users.phone_number')--}}
{{--                                       }}</label>--}}

                                <div class="col-md-12">
                                    <input id="phone_number" type="text"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           placeholder="{{ __('pages/users.phone_number')
                                       }}"
                                           name="phone_number" value="{{ old('phone_number') }}" required>

                                    @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-12 text-left">
                                <div class="form-input">
                                    <button class="btn btn-primary pull-right ali-submit-btn ">{{ trans('dashboard.create_account')
                        }}</button>
                                </div>

                            </div>

                        </div><!--body-->
                    </div><!--card-->

                </div><!--col-6-->

            </div>


{{--            <div class="row justify-content-center">--}}
{{--                <div class="col-md-12 text-left">--}}
{{--                    <div class="form-input">--}}
{{--                        <button class="btn btn-primary pull-right ali-submit-btn ">{{ trans('dashboard.create_account')--}}
{{--                        }}</button>--}}
{{--                    </div>--}}

{{--                </div>--}}


{{--            </div>--}}

        </form>


    </div>
@endsection
