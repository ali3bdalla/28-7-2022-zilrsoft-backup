@extends('layouts.master2')



@section('title',"انشاء حساب مالي للمنشأة")
@section('desctipion',__('pages/settings.payments_create'))
@section('route',route('management.gateways.index'))


@section('content')
    <div class="box">
        <add-new-payment-account-component :organization_gateways='@json($organization_gateways)' :banks='@json($banks)'>
        </add-new-payment-account-component>
    </div>
@stop
