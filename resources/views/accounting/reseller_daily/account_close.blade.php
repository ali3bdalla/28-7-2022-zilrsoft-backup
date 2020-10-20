@extends('accounting.layout.master')

@section('title',__('sidebar.account_close'))






@section("content")
    <accounting-period-account-close-component
            :in-amount='@json(roundMoney($inAmount))'
            :out-amount='@json(roundMoney($outAmount))'
            :remaining-accounts-balance='@json(roundMoney($remainingAccountsBalanceAmount))'
            :gateways='@json($gateways)'
    ></accounting-period-account-close-component>
@endsection

