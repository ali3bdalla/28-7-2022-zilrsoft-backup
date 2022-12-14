@extends('accounting.layout.master')

@section('title', __('sidebar.account_close'))

@section('buttons')

    <a class="btn btn-custom-primary" href="{{ route('daily.reseller.accounts_transactions.create') }}">انشاء تحويل</a>
    <a class="btn btn-custom-primary" href="{{ route('daily.reseller.closing_accounts.index') }}">الاقفالات
    </a>



@endsection
@section('content')

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>رقم العملية</th>
                    <th>التاريخ</th>
                    <th>من</th>
                    <th>الى</th>
                    <th>المبلغ الاجمالي</th>
                    <th>المبلغ المحول</th>
                    <th>المتبقي</th>
                    <th>الحالة</th>
                </tr>
                </thead>

                @foreach($managerCloseAccountList as $transaction)
                    <tbody>
                    <tr class="">
                        <td>TRANS-{{ $transaction->creator->id }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->fromAccount->locale_name }}</td>
                        <td>{{ $transaction->toAccount->locale_name }}</td>
                        <td>{{ ($transaction->amount + $transaction->remaining_accounts_balance)  }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->remaining_accounts_balance }}</td>
                        <td>
                            @if($transaction->is_pending)
                                منتظرة
                            @else
                                مقبولة
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
