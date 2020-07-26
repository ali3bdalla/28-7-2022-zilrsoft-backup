<?php $total_debit = 0; $total_credit = 0;?>

<tbody class="text-center">
<?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

    <?php if(!in_array($transaction['description'],['to_cogs','to_gateway',
                                                   'to_products_sales_discount','to_services_sales_discount',
                                                   'to_other_services_sales_discount','to_stock'])): ?>

	    <?php $total_credit = $total_credit + $transaction['amount']?>

         <tr>
             <td class="date_field_center"><?php echo e($transaction->created_at); ?></td>
             <td><?php echo e($transaction->creditable !== null ? $transaction->creditable->locale_name : ""); ?></td>
             <td></td>
             <td><?php echo e(money_format("%i", $transaction->amount)); ?></td>
         </tr>


    <?php else: ?>

	    <?php $total_debit = $total_debit + $transaction['amount']?>
         <tr>
             <td class="date_field_center"><?php echo e($transaction->created_at); ?></td>
             <td><?php echo e($transaction->debitable !== null ? $transaction->debitable->locale_name : ""); ?></td>
             <td><?php echo e(money_format("%i", $transaction->amount)); ?></td>
             <td></td>
         </tr>

    <?php endif; ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody>

<thead>
<th>المجموع</th>
<th></th>
<th><?php echo e(money_format("%i",$total_debit)); ?></th>
<th><?php echo e(money_format("%i",$total_credit)); ?></th>
</thead>

<?php if(money_format("%i",$total_debit)!=money_format("%i",$total_credit)): ?>
    <script>
        alert('توجد مشكلة بالعمليات المحاسبية لهذه الفاتورة')
    </script>
<?php endif; ?>
<?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/include/invoice/transactions/sale.blade.php ENDPATH**/ ?>