@can('view transactions')
    <div class="panel panel-info">
        <div class="panel-heading  bg-custom-primary">
            <label>{{ trans('sidebar.transactions') }}</label>
        </div>
        <table class="table table-bordered table-hover text-center">
            <thead>
            <th>{{ trans('pages/transactions.date') }}</th>
            <th>{{ trans('pages/transactions.account_name') }}</th>
            <th>{{ trans('pages/transactions.debit') }}</th>
            <th>{{ trans('pages/transactions.credit') }}</th>
            </thead>
            <?php $total_debit = 0; $total_credit = 0;?>
        <tbody class="text-center">
        @foreach($transactions as $transaction)

            @if($transaction->type  == 'credit')

                <?php $total_credit = $total_credit + $transaction['amount']?>

                <tr>
                    <td class="date_field_center">{{ $transaction->created_at }}</td>
                    <td>{{ $transaction->account_name}}</td>
                    <td></td>
                    <td>{{ moneyFormatter($transaction->amount) }}</td>
                </tr>


            @else

                <?php $total_debit = $total_debit + $transaction['amount']?>
                <tr>
                    <td class="date_field_center">{{ $transaction->created_at }}</td>
                    <td>{{  $transaction->account_name }}</td>
                    <td>{{  moneyFormatter($transaction->amount) }}</td>
                    <td></td>
                </tr>

            @endif

        @endforeach
        </tbody>

        <thead>
        <th>المجموع</th>
        <th></th>
        <th>{{ moneyFormatter($total_debit) }}</th>
        <th>{{ moneyFormatter($total_credit) }}</th>
        </thead>



        </table>
    </div>
@endcan
