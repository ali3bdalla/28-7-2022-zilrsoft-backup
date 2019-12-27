@foreach($transactions as $transaction)

    @if(in_array($transaction['description'],['to_cogs','to_gateway',
    'to_products_sales_discount','to_services_sales_discount',
    'to_other_services_sales_discount','to_stock']))

	    <?php $total_credit = $total_credit + $transaction['amount']?>
         <td class="date_field_center">{{ $transaction->created_at }}</td>
         <td>{{ $transaction->creditable->locale_name }}</td>
         <td></td>
         <td>{{money_format("%i", $transaction->amount) }}</td>
         </tr>


    @else
	    <?php $total_debit = $total_debit + $transaction['amount']?>
         <tr>
             <td class="date_field_center">{{ $transaction->created_at }}</td>
             <td> {{
                                                         $transaction->debitable->locale_name }}</td>
             <td>{{money_format("%i", $transaction->amount) }}</td>
             <td></td>
         </tr>

    @endif

@endforeach


<thead>
<th>المجموع</th>
<th></th>
<th>{{ money_format("%i",$total_debit) }}</th>
<th>{{ money_format("%i",$total_credit) }}</th>
</thead>
</table>
</div>