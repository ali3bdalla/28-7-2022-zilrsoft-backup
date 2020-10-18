<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view transactions')): ?>
    <div class="panel panel-info">
        <div class="panel-heading  bg-custom-primary">
            <label><?php echo e(trans('sidebar.transactions')); ?></label>
        </div>
        <table class="table table-bordered table-hover text-center">
            <thead>
            <th><?php echo e(trans('pages/transactions.date')); ?></th>
            <th><?php echo e(trans('pages/transactions.account_name')); ?></th>
            <th><?php echo e(trans('pages/transactions.debit')); ?></th>
            <th><?php echo e(trans('pages/transactions.credit')); ?></th>
            </thead>
            <?php $total_debit = 0; $total_credit = 0;?>
        <tbody class="text-center">
        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <?php if($transaction->type  == 'credit'): ?>

                <?php $total_credit = $total_credit + $transaction['amount']?>

                <tr>
                    <td class="date_field_center"><?php echo e($transaction->created_at); ?></td>
                    <td><?php echo e($transaction->account_name); ?></td>
                    <td></td>
                    <td><?php echo e(displayMoney($transaction->amount)); ?></td>
                </tr>


            <?php else: ?>

                <?php $total_debit = $total_debit + $transaction['amount']?>
                <tr>
                    <td class="date_field_center"><?php echo e($transaction->created_at); ?></td>
                    <td><?php echo e($transaction->account_name); ?></td>
                    <td><?php echo e(displayMoney($transaction->amount)); ?></td>
                    <td></td>
                </tr>

            <?php endif; ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>

        <thead>
        <th>المجموع</th>
        <th></th>
        <th><?php echo e(displayMoney($total_debit)); ?></th>
        <th><?php echo e(displayMoney($total_credit)); ?></th>
        </thead>









        </table>


    </div>
<?php endif; ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/include/invoice/view_transactions.blade.php ENDPATH**/ ?>