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
                    <th>رقم العملية </th>
                    <th>التاريخ</th>
                    <th>الى</th>
                    <th>المبلغ الاجمالي</th>
                    <th>المبلغ المحول</th>
                    <th>المتبقي</th>
                    <th>الحالة</th>
                </tr>
                </thead>

                @foreach($managerCloseAccountList as $transaction)
                    <?php $total_amount = $transaction->container->transactions()->where([['type','debit']])
			            ->withoutGlobalScope
			            ('pendingTransactionScope')->sum('amount'); ?>
		            <tbody>
        
                    <tr class="">
                        <td>TRANS-{{ $transaction->creator->id }}</td>
                        <td>{{ $transaction->created_at }}</td>
                        <td>{{ $transaction->receiver->locale_name }}</td>
                        <td>{{ money_format("%i",$total_amount)}}</td>

                        <td>{{  money_format("%i",$transaction->amount) }}</td>
                        <td>{{ money_format("%i",$total_amount - $transaction->amount) }}</td>
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