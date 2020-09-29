@extends('accounting.layout.master')

@section('title',__('sidebar.account_close'))






@section("content")
    <accounting-period-account-close-component
            :period-sales-amount='@json(roundMoney($periodSalesAmount))'
            :last-remaining-transfer='@json(roundMoney($lastRemainingTransferAmount))'
            :gateways='@json($gateways)'
    ></accounting-period-account-close-component>
@endsection

