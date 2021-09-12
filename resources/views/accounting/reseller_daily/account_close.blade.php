@extends('accounting.layout.master')

@section('title',__('sidebar.account_close'))






@section("content")
    <accounting-period-account-close-component
            :in-amount='@json(moneyFormatter($inAmount))'
            :out-amount='@json(moneyFormatter($outAmount))'
            :remaining-accounts-balance='@json(moneyFormatter($remainingAccountsBalanceAmount))'
            :gateways='@json($gateways)'
    ></accounting-period-account-close-component>
@endsection

