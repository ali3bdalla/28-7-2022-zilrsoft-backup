<?php
    $padding+=20
?>


<?php if($account->children()->count() === 0): ?>
    <?php
        /** @var TYPE_NAME $account */
        $result =  $account->getTrialBalanceData();
       $totalCreditAmount= $totalCreditAmount  + (float)$result['debit_amount'];
       $totalDebitAmount = $totalCreditAmount + (float)$result['credit_amount'];
       $totalCreditBalance =$totalCreditBalance +  (float)$result['debit_balance'];
       $totalDebitBalance= $totalDebitBalance + (float)$result['credit_balance'];

    ?>
    
    
    <tr>
        <td><?php echo e($account->children()->count()); ?>   <?php echo e($account->id); ?></td>
        <td><a target="_blank" href="<?php echo e(route('accounting.accounts.show',$account->id)); ?>"><?php echo e($account->locale_name); ?></a></td>
        <td><?php echo e(money_format('%i',$result['debit_amount'])); ?></td>
        <td><?php echo e(money_format('%i',$result['credit_amount'])); ?></td>
        <td><?php echo e(money_format('%i',$result['debit_balance'])); ?></td>
        <td><?php echo e(money_format('%i',$result['credit_balance'])); ?></td>
    </tr>
    
<?php else: ?>
    <?php $__currentLoopData = $account->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tbody style="margin-right: 5px !important;" class="table-body-child">
        <?php if($account2->children()->count() > 0): ?>
            <tr>
                <td></td>
                <td colspan="" class="text-bold" style="padding-right:<?php echo e($padding); ?>px;text-align: right !important;"><?php echo e($account2->locale_name); ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

            </tr>
        <?php endif; ?>
        <?php if ($__env->exists('accounting::trial_balance.table_cell',['account' => $account2])) echo $__env->make('accounting::trial_balance.table_cell',['account' => $account2], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </tbody>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>






<?php /**PATH /usr/local/var/www/workspace/zilrsoft/Modules/Accounting/Resources/views/trial_balance/table_cell.blade.php ENDPATH**/ ?>