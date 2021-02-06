<?php $total_debit = 0; $total_credit = 0;?>
<tbody class="text-center">
@foreach($transactions as $transaction)
    @if($transaction['description']=='to_item' || $transaction['description']=='to_tax')
	    <?php $total_debit = $total_debit + $transaction['amount']?>
         <tr>
             <td class="date_field_center">{{ $transaction->created_at }}</td>
             <td>{{ $transaction->debitable->locale_name }}</td>
             <td>{{moneyFormatter( $transaction->amount) }}</td>
             <td></td>
         </tr>

    @else
	    <?php $total_credit = $total_credit + $transaction['amount']?>
         <tr>
             <td class="date_field_center">{{ $transaction->created_at }}</td>
             <td>{{ $transaction->creditable->locale_name }}</td>
             <td></td>
             <td>{{moneyFormatter( $transaction->amount) }}</td>
         </tr>
    @endif
@endforeach
</tbody>
<thead>
<th>{{ trans('pages/transactions.amount') }}</th>
<th></th>
<th>{{ moneyFormatter($total_debit) }}</th>
<th>{{ moneyFormatter($total_credit) }}</th>
</thead>

@if(moneyFormatter($total_debit)!=moneyFormatter($total_credit))
    <script>
        alert('توجد مشكلة بالعمليات المحاسبية لهذه الفاتورة')
    </script>
@endif