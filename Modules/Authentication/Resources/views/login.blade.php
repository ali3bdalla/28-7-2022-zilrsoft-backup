@extends('authentication::layouts.master')

@section('content')
    <div class=" p-0 md:p-10 mx-2 md:mx-64 md:px-32">
        <div class="">
            <div class="">
                <h3 class="text-3xl mb-10">@lang('authentication::text.sign_in')</h3>
            </div>
            <div class=" ">
                <form method="POST" action="{{ route('authentication.perform_login') }}">
                    @csrf
                    <div class="">
                        <div class="">
                            <span class=""><i class="fas fa-user"></i></span>
                        </div>
                        <input type="email" class=" rounded-lg w-full px-6 py-2 text-2xl
                                shadow-lg "
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>
                    <div class="">
                        <div class="">
                            <span class=""><i class=""></i></span>
                        </div>
                        <input id="password" type="password" style="direction: ltr"
                               class="rounded-lg w-full px-6 py-2 text-2xl
                                shadow-lg mt-2" name="password" required
                               autocomplete="current-password">

                        @error('password')
                        <span class="">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <input type="submit" value="@lang('authentication::text.sign_in')"
                               class="bg-blue-300
                               text-xl
                               shadow-lg
                               object-center mt-3 w-full p-3 rounded-lg hover:bg-blue-500
                               text-white">
                    </div>
                </form>


            </div>
            <div class="shadow-md pb-10">
                <div class="p-3 mt-10">
                    <a href="{{ route('authentication.register') }}" class="text-blue-400"> @lang('authentication::text.register') </a>
                </div>
                <div class="">
                    <a href="#" class="text-blue-400"> @lang('authentication::text.forget_password') </a>
                </div>
            </div>
        </div>
    </div>
@endsection