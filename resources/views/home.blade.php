@extends('layouts.master2')


@section('title', auth()->user()->organization->title_ar )
@section('route',route('management.dashboard'))




@section('content')
    <div class="box">

        <div class="card-body">
            <div class="columns">
                <div class="column"></div>
                <div class="column text-left">
                    <live-server-date-and-time></live-server-date-and-time>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <hr>
        <div class="columns">
            <div class="column">
                <div class="box">
                    <h1 class="title text-center">
                        {{ __('sidebar.sales') }}
                    </h1>
                    <span class="subtitle">{{ money_format('%i',auth()->user()->organization->sales->count()) }}</span>
                </div>
            </div>
            <div class="column">
                <div class="box">
                    <h1 class="title text-center">
                        {{ __('sidebar.purchases') }}
                    </h1>
                    <span class="subtitle">{{ money_format('%i',auth()->user()->organization->sales->count()) }}</span>
                </div>
            </div>
{{--            <div class="column">--}}
{{--                <div class="box">--}}
{{--                    <h1 class="title text-center">--}}
{{--                        {{ __('sidebar.sales') }}--}}
{{--                    </h1>--}}
{{--                    <span class="subtitle">{{ money_format('%i',auth()->user()->organization->sales->count()) }}</span>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>





    </div>

@endsection
