@extends('accounting.layout.master')

@section('title', __('sidebar.account_close'))

@section('buttons')

    <a class="btn btn-custom-primary" href="{{ route('daily.reseller.closing_accounts.create') }}">انشاء اقفال</a>
    <a class="btn btn-custom-primary" href="{{ route('daily.reseller.accounts_transactions.index') }}">التحويلات
    </a>

@endsection
@section('content')

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>رقم العملية</th>
                    <th>من</th>
                    <th>الى</th>
                    <th>المبلغ المفترض</th>
                    <th>المبلغ الفعلي</th>
                    <th>الفرق</th>
                </tr>
                </thead>

                @foreach($managerCloseAccountList as $transaction)
                    <tbody>
                    <tr class="">
                        <td>CSH-{{ $transaction->id }}</td>
                        <td>{{ $transaction->from }}</td>
                        <td>{{ $transaction->to }}</td>
                        <td>{{ moneyFormatter($transaction->amount) }}</td>
                        @if($transaction->shortage_amount<0)
                            <td>{{ moneyFormatter(($transaction->amount - abs($transaction->shortage_amount ))) }}</td>

                        @else
                            <td>{{ moneyFormatter(($transaction->amount + abs( $transaction->shortage_amount )))}}</td>

                        @endif
                        <td>
                            @if($transaction->shortage_amount>0)
                                <span class="text-green">{{ moneyFormatter($transaction->shortage_amount) }}</span>
                            @elseif($transaction->shortage_amount<0)
                                <span class="text-danger">{{ moneyFormatter($transaction->shortage_amount) }}</span>
                            @else
                                {{ moneyFormatter($transaction->shortage_amount) }}

                            @endif
                        </td>
                    </tr>
                    </tbody>
                @endforeach

                {{ $managerCloseAccountList->links() }}
            </table>
        </div>
    </div>
@endsection
