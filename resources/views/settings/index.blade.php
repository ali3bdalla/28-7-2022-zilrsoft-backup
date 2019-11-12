@extends('layouts.master2')

@section('title',__('sidebar.settings'))
@section('desctipion',__('pages/settings.settings'))
@section('route',route('management.users.index'))


@section('content')
    <div class="">
         <hr>
        <div class="columns">
{{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.general') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
            <div class="column">
                <a href="{{ route('management.settings.payment_accounts') }}" class="box">
                    <h1 class="title text-center">
                        الحسابات المالية للمنشأة
                    </h1>
                </a>
            </div>
            <div class="column">
                <a href="{{ route('management.expenses.index') }}" class="box">
                    <h1 class="title text-center">
                        {{ __('pages/settings.expenses') }}
                    </h1>
                </a>
            </div>
        </div>


    {{--        url='{{route('management.gateways.index')}}'--}}
    {{--        title='{{ __('sidebar.payments_methods') }}'--}}
    {{--        icon='fa fa-gem'--}}

{{--        <div class="columns">--}}
{{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.archive') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.permissions') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.files') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}




{{--        <div class="columns">--}}
{{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.general') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.payments') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.security') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--        </div>--}}



        <div class="columns">

{{--            <div class="column">--}}
{{--                <a href="{{ route('management.gateways.index') }}" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        البوابات المالية--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
                {{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.archive') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
{{--            <div class="column">--}}
{{--                <a href="" class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.permissions') }}--}}
{{--                    </h1>--}}
{{--                </a>--}}
{{--            </div>--}}
            <div class="column">
                <a href="{{ route('management.settings.global.printers') }}" class="box">
                    <h1 class="title text-center">
                        ادارة الطابعات
                    </h1>
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('pages/settings.files') }}--}}
{{--                    </h1>--}}

{{--                    <li class="nav-item"><a href="{{ route('management.settings.global.printers') }}">الطابعات--}}
{{--                            المحلية</a>--}}
{{--                    </li>--}}
                </a>
            </div>
        </div>







    </div>
@endsection
