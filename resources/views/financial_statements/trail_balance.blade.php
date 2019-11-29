@extends('layouts.master2')

@section('title',__('sidebar.financial_statements'))
@section('route',route('management.financial_statements.index'))


@section('content')
    <div class="box">
        <div class="panel">
            <table class="table table-bordered is-bordered text-center">
                <thead>
                <tr>
                    <th></th>
                    <th colspan="2">المجاميع</th>
                    <th colspan="2">الارصدة</th>
                </tr>


                <tr>
                    <th>اسم الحساب</th>
                    <th>مدين</th>
                    <th>دائن</th>
                    <th>مدين</th>
                    <th>دائن</th>
                </tr>
                </thead>
                <tbody>
                @foreach($accounts as $account)
                    <tr>
                        <td width="20%">{{ $account['locale_name'] }}</td>
                        <td>{{ money_format("%i", $account['total_debit'])  }}</td>
                        <td>{{money_format("%i", $account['total_credit']) }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
