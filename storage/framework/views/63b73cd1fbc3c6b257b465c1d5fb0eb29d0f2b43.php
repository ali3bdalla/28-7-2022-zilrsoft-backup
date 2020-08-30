<tbody class="text-center">
<?php $total_debit = 0; $total_credit = 0;?>
<?php $__currentLoopData = $invoice_transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index =>  $invoice_transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr style="border:none">
        <?php if($purchase->invoice_type=='r_purchase'): ?>

            <?php if($invoice_transaction['description']=='to_item' || $invoice_transaction['description']=='to_tax'): ?>
			    <?php $total_credit = $total_credit + $invoice_transaction['amount'];?>
                <th><?php echo e(money_format("%i", $invoice_transaction['amount'])); ?></th>
                <th></th>
                <th><?php if(!empty( $invoice_transaction->creditable)): ?><?php echo e($invoice_transaction->creditable->locale_name); ?><?php else: ?>
                        - <?php endif; ?> </th>

            <?php else: ?>

			    <?php $total_debit = $total_debit + $invoice_transaction['amount']?>
                <th></th>
                <th><?php echo e(money_format("%i", $invoice_transaction['amount'])); ?></th>
                <th><?php if(!empty( $invoice_transaction->debitable)): ?><?php echo e($invoice_transaction->debitable->locale_name); ?><?php else: ?>
                        - <?php endif; ?> </th>
            <?php endif; ?>





        <?php else: ?>

            <?php if($invoice_transaction['description']=='to_item' || $invoice_transaction['description']=='to_tax'): ?>
			    <?php $total_debit = $total_debit + $invoice_transaction['amount']?>
                <th></th>
                <th><?php echo e(money_format("%i", $invoice_transaction['amount'])); ?></th>
                <th><?php if(!empty( $invoice_transaction->debitable)): ?><?php echo e($invoice_transaction->debitable->locale_name); ?><?php else: ?>
                        - <?php endif; ?> </th>

            <?php else: ?>
			    <?php $total_credit = $total_credit + $invoice_transaction['amount']?>
                <th><?php echo e(money_format("%i", $invoice_transaction['amount'])); ?></th>
                <th></th>
                <th><?php if(!empty( $invoice_transaction->creditable)): ?><?php echo e($invoice_transaction->creditable->locale_name); ?><?php else: ?>
                        - <?php endif; ?> </th>
            <?php endif; ?>


        <?php endif; ?>

        <?php if($index==0): ?>

            <th width="20%" style="vertical-align: inherit;" rowspan="<?php echo e(count
                                ($invoice_transactions)); ?>"><a href="<?php echo e(route('accounting.purchases.show',
                                $purchase->invoice->id)); ?>"><?php echo e($purchase->invoice->title); ?></a></th>
            <th width="10%" style="vertical-align: inherit;"
                rowspan="<?php echo e(count($invoice_transactions)); ?>"><a href="<?php echo e(route('accounting.transactions.show',$transaction->id)); ?>"><?php echo e($invoice_transaction['id']); ?></a></th>
            <th width="15%" style="vertical-align: inherit;" class="datedirection" rowspan="<?php echo e(count
                                ($invoice_transactions)); ?>"><?php echo e($transaction['created_at']); ?></th>
        <?php endif; ?>

    </tr>




<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<tr style="background-color: #eeeeee">
    <th><?php echo e(money_format("%i",$total_debit)); ?></th>
    <th><?php echo e(money_format("%i",$total_credit)); ?></th>
    <th>المجموع</th>
    <th></th>
    <th></th>
    <th></th>

</tr>
</tbody>


<?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/transactions/purchase.blade.php ENDPATH**/ ?>