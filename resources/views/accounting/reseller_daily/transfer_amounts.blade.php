@extends('accounting.layout.master')

@section('title',__('pages/reseller_daily.transfer_amounts'))






@section("content")
    <accounting-reseller-daily-transfer-amounts-component
            :gateways='@json($manager_gateways)'
            :to-gateways='@json($gateways)'
    >

    </accounting-reseller-daily-transfer-amounts-component>
@endsection

