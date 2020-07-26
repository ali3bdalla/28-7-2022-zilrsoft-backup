@extends('accounting::layouts.master')

@section('content')
    <div class="table">
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td colspan="2">المجاميع</td>
                <td colspan="2">الارصدة</td>

            </tr>
            <tr>
                <td>#</td>
                <td>اسم الحساب</td>
                <td>مدين</td>
                <td>دائن</td>
                <td>مدين</td>
                <td>دائن</td>

            </tr>
        </thead>
        <tbody>
        @foreach($accounts as $account)
            <tr>
                <td>{{ $account->id }}</td>
                <td>{{ $account->locale_name }}</td>
                <td>{{ money_format('%i',$account->debit_amount) }}</td>
                <td>{{ money_format('%i',$account->credit_amount)}}</td>
                <td>{{ money_format('%i',$account->debit_balance)}}</td>
                <td>{{ money_format('%i',$account->credit_balance)}}</td>

            </tr>
        @endforeach
        </tbody>
        <thead>
        <tr>
            <th></th>
            <th></th>
            <th>{{ money_format('%i',$totalDebitAmount) }}</th>
            <th>{{ money_format('%i',$totalCreditAmount)}}</th>
            <th>{{ money_format('%i',$totalDebitBalance)}}</th>
            <th>{{ money_format('%i',$totalCreditBalance)}}</th>

        </tr>
        </thead>
    </table>
    </div>

@endsection
