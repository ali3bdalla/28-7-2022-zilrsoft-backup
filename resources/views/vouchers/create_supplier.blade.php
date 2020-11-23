@extends('accounting.layout.master')

@section('title','سند مصاريف')



@section('page_css')
    <script defer>
        window.translator = `@json(trans('pages/vouchers'))`
    </script>
@stop



@section('content')


    <supplier-voucher
            :expenses-accounts='@json($expensesAccounts)'
            :accounts='@json($accounts)'
    >
    </supplier-voucher>


@stop
