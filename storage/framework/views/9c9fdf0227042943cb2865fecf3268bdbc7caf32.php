<?php $total_debit = 0; $total_credit = 0;?>
<tbody class="text-center">
<?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($transaction['description']=='to_item' ||
               $transaction['description']=='to_tax'): ?>
	    <?php $total_credit = $total_credit + $transaction['amount']?>
         <tr>
             <td class="date_field_center"><?php echo e($transaction->created_at); ?></td>
             <td><?php echo e($transaction->creditable->locale_name); ?></td>
             <td></td>
             <td><?php echo e(money_format("%i", $transaction->amount)); ?></td>
         </tr>
    <?php else: ?>
	    <?php $total_debit = $total_debit + $transaction['amount']?>
         <tr>
             <td class="date_field_center"><?php echo e($transaction->created_at); ?></td>
             <td><?php echo e($transaction->debitable->locale_name); ?></td>
             <td><?php echo e(money_format("%i", $transaction->amount)); ?></td>
             <td></td>
         </tr>
    <?php endif; ?>
    
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>
<thead>
<th><?php echo e(trans('pages/transactions.amount')); ?></th>
<th></th>
<th><?php echo e(money_format("%i",$total_debit)); ?></th>
<th><?php echo e(money_format("%i",$total_credit)); ?></th>
</thead>
<?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/include/invoice/transactions/return_purchase.blade.php ENDPATH**/ ?>