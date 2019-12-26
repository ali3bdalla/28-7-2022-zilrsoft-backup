<?php $total_debit = 0; $total_credit = 0;?>
<tbody class="text-center">
@foreach($transactions as $transaction)
    @if($transaction['description']=='to_item' ||
               $transaction['description']=='to_tax')
	    <?php $total_credit = $total_credit + $transaction['amount']?>
         <tr>
             <td class="date_field_center">{{ $transaction->created_at }}</td>
             <td>{{ $transaction->creditable->locale_name }}</td>
             <td></td>
             <td>{{money_format("%i", $transaction->amount) }}</td>
         </tr>
    @else
	    <?php $total_debit = $total_debit + $transaction['amount']?>
         <tr>
             <td class="date_field_center">{{ $transaction->created_at }}</td>
             <td>{{ $transaction->debitable->locale_name }}</td>
             <td>{{money_format("%i", $transaction->amount) }}</td>
             <td></td>
         </tr>
    @endif

@endforeach
</tbody>
<thead>
<th>{{ trans('pages/transactions.amount') }}</th>
<th></th>
<th>{{ money_format("%i",$total_debit) }}</th>
<th>{{ money_format("%i",$total_credit) }}</th>
</thead>
