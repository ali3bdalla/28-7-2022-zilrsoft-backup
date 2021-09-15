<tbody class="text-center">
<?php $total_debit = 0; $total_credit = 0;?>
@foreach($invoice_transactions as $index =>  $invoice_transaction)
    <tr style="border:none">
        @if($purchase->invoice_type=='return_purchase')

            @if($invoice_transaction['description']=='to_item' || $invoice_transaction['description']=='to_tax')
			    <?php $total_credit = $total_credit + $invoice_transaction['amount'];?>
                <th>{{ moneyFormatter( $invoice_transaction['amount']) }}</th>
                <th></th>
                <th>@if(!empty( $invoice_transaction->creditable)){{ $invoice_transaction->creditable->locale_name }}@else
                        - @endif </th>

            @else

			    <?php $total_debit = $total_debit + $invoice_transaction['amount']?>
                <th></th>
                <th>{{ moneyFormatter( $invoice_transaction['amount']) }}</th>
                <th>@if(!empty( $invoice_transaction->debitable)){{ $invoice_transaction->debitable->locale_name }}@else
                        - @endif </th>
            @endif

        @else

            @if($invoice_transaction['description']=='to_item' || $invoice_transaction['description']=='to_tax')
			    <?php $total_debit = $total_debit + $invoice_transaction['amount']?>
                <th></th>
                <th>{{moneyFormatter( $invoice_transaction['amount']) }}</th>
                <th>@if(!empty( $invoice_transaction->debitable)){{ $invoice_transaction->debitable->locale_name }}@else
                        - @endif </th>

            @else
			    <?php $total_credit = $total_credit + $invoice_transaction['amount']?>
                <th>{{ moneyFormatter( $invoice_transaction['amount']) }}</th>
                <th></th>
                <th>@if(!empty( $invoice_transaction->creditable)){{ $invoice_transaction->creditable->locale_name }}@else
                        - @endif </th>
            @endif


        @endif

        @if($index==0)

            <th width="20%" style="vertical-align: inherit;" rowspan="{{ count
                                ($invoice_transactions) }}"><a href="{{route('accounting.purchases.show',
                                $purchase->invoice->id)
                                }}">{{$purchase->invoice->title}}</a></th>
            <th width="10%" style="vertical-align: inherit;"
                rowspan="{{ count($invoice_transactions) }}"><a href="{{ route('accounting.transactions.show',$transaction->id)
                                    }}">{{
                                $invoice_transaction['id'] }}</a></th>
            <th width="15%" style="vertical-align: inherit;" class="datedirection" rowspan="{{
                                count
                                ($invoice_transactions) }}">{{
                                $transaction['created_at'] }}</th>
        @endif

    </tr>




@endforeach

<tr style="background-color: #eeeeee">
    <th>{{ moneyFormatter($total_debit) }}</th>
    <th>{{ moneyFormatter($total_credit) }}</th>
    <th>المجموع</th>
    <th></th>
    <th></th>
    <th></th>

</tr>
</tbody>


