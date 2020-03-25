@extends('accounting.layout.master')


@section('title', __('sidebar.chart_of_accounts'))

@section('page_css')
    <style>
        .group {
            background-color: white;
            padding-right: 43px;
            font-size: 18px;
            margin-bottom: 2px;
            border-right: 1px solid #e9e4e5;
            margin-right: 20px;
            padding-top: 4px;
        }

        .title {
            border: 1px solid #faf9f9;
            padding: 7px;
            background: #eee;
            width: 650px;
        }

        .amount {
            padding-left: 20px;
            float: left;
            font-size: 19px;
            font-weight: bold;
        }
        .rgRed {
            background-color: #fed5c5;
        }

        .butn {
            float: left;
            margin-left: 15px;


        }

        .list-group-item {
            border:none !important;
            padding:0px
        }
    </style>
@endsection

@section('buttons')
    @can("create chart")
        <a class="btn btn-custom-primary" href="{{ route('accounting.accounts.create') }}">اضافة حساب</a>
    @endcan
@endsection
@section('content')

    @foreach($accounts as $account)
        @includeIf('accounting.charts.row',['account' => $account])
    @endforeach

@endsection