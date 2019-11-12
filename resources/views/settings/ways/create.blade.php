@extends('layouts.master2')



@section('title','create payment method ')
@section('desctipion','create payment method ')
@section('route',route('management.gateways.index'))

@section('translator')
    <script defer>
        window.translator = '@json(trans('pages/items'))'
    </script>
@stop

@section('content')
    <div class="message-header is-primary">
        create new payments method
    </div>
    <div class="box">




        <form method="post" action="{{route('management.gateways.index')}}">
            @csrf

{{--            <custom-pay-way-form-component></custom-pay-way-form-component>--}}
        </form>

    </div>

@stop
