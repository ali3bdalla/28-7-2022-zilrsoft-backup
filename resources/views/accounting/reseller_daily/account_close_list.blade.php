@extends('accounting.layout.master')

@section('title', __('sidebar.account_close'))

@section('buttons')

    <a class="btn btn-custom-primary" href="{{ route('accounting.reseller_daily.account_close') }}">انشاء اقفال</a>
    <a class="btn btn-custom-primary" href="{{ route('accounting.reseller_daily.transfer_amounts') }}">تحويل الاموال </a>

@endsection
@section('content')

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>من</th>
                    <th>الى</th>
                    <th>القيمة</th>
                    <th>العجز</th>
                </tr>
                </thead>
                <tbody>
                @foreach($managerCloseAccountList as $transaction)
                    <tr>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ money_format("%i",$transaction->amount) }}</td>
                        <td>{{ money_format("%i",$transaction->shortage_amount) }}</td>
                    </tr>
                @endforeach
                </tbody>
                {{ $managerCloseAccountList->links() }}
            </table>
        </div>
    </div>
@endsection