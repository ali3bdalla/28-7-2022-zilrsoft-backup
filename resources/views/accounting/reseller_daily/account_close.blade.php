@extends('accounting.layout.master')

@section('title',__('sidebar.account_close'))






@section("content")
    <accounting-period-account-close-component
            :period-sales-amount='@json(money_format("%i",$periodSalesAmount))'
            :last-remaining-transfer='@json(money_format("%i",$lastRemainingTransferAmount))'
            :gateways='@json($gateways)'
    ></accounting-period-account-close-component>
@endsection

