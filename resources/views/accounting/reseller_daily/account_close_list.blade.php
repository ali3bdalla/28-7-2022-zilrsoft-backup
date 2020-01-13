@extends('accounting.layout.master')

@section('title', __('sidebar.account_close'))

@section('buttons')

    <a class="btn btn-custom-primary" href="{{ route('accounting.reseller_daily.account_close') }}">انشاء اقفال</a>
    <a class="btn btn-custom-primary" href="{{ route('accounting.reseller_daily.transfer_amounts') }}">تحويل
        الاموال </a>

@endsection
@section('content')

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>نوع المعاملة</th>
                    <th>من</th>
                    <th>الى</th>
                    <th>القيمة</th>
                    <th>العجز/الحالة</th>
                </tr>
                </thead>
                <tbody>
                @foreach($managerCloseAccountList as $transaction)
                    @if($transaction['transaction_type']=='close_account')
                        <tr>
                            <td>تقفيل الحساب</td>
                            <td>{{ $transaction->close_account_start_date }}</td>
                            <td>{{ $transaction->close_account_end_date }}</td>
                            <td>{{ money_format("%i",$transaction->amount) }}</td>
                            <td>{{ money_format("%i",$transaction->shortage_amount) }}</td>
                        </tr>
                    @else
                        {{----}}
                        <tr>
                            <td>تحويل</td>
                            <td>{{ $transaction->creator->locale_name }}</td>
                            <td>{{ $transaction->receiver->locale_name }}</td>
                            <td>{{ money_format("%i",$transaction->amount) }}</td>
                            <td>
                                @if($transaction->is_pending)
                                    منتظرة
                                @else
                                    مقبولة
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
                {{ $managerCloseAccountList->links() }}
            </table>
        </div>
    </div>
@endsection