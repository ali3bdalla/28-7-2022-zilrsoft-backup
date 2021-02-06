<tr class="info">
    <th class="text-center ">{{$transaction->created_at}}</th>
    <th class="text-center ">{{$transaction->id}}</th>
    <th class="text-center ">
        -
    </th>
    <th class="text-center "><a href="{{ route('accounting.accounts.show',$transaction->id) }}">
            {{ $transaction->locale_name }}</a></th>
    <th class="text-center ">{{ moneyFormatter($transaction->debit_amount )}}</th>
    <th class="text-center ">{{ moneyFormatter($transaction->credit_amount )}}</th>

    <th class="text-center ">{{ moneyFormatter($transaction->total_debit_amount)  }}</th>
    <th class="text-center ">{{ moneyFormatter($transaction->total_credit_amount)  }}</th>
</tr>
