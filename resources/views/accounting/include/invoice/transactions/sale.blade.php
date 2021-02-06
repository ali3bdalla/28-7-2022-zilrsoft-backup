<?php $total_debit = 0; $total_credit = 0;?>

<tbody class="text-center">
@foreach($transactions as $transaction)

    @if(!in_array($transaction['description'],['to_cogs','to_gateway',
                                                   'to_products_sales_discount','to_services_sales_discount',
                                                   'to_other_services_sales_discount','to_stock']))

	    <?php $total_credit = $total_credit + $transaction['amount']?>

         <tr>
             <td class="date_field_center">{{ $transaction->created_at }}</td>
             <td>{{ $transaction->creditable !== null ? $transaction->creditable->locale_name : ""}}</td>
             <td></td>
             <td>{{moneyFormatter( $transaction->amount) }}</td>
         </tr>


    @else

	    <?php $total_debit = $total_debit + $transaction['amount']?>
         <tr>
             <td class="date_field_center">{{ $transaction->created_at }}</td>
             <td>{{ $transaction->debitable !== null ? $transaction->debitable->locale_name : "" }}</td>
             <td>{{moneyFormatter( $transaction->amount) }}</td>
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

@if(moneyFormatter($total_debit)!=moneyFormatter($total_credit))
    <script>
        alert('توجد مشكلة بالعمليات المحاسبية لهذه الفاتورة')
    </script>
@endif
