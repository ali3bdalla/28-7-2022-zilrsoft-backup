<?php $__env->startSection('title', __('sidebar.account_close')); ?>

<?php $__env->startSection('buttons'); ?>

    <a class="btn btn-custom-primary" href="<?php echo e(route('daily.reseller.closing_accounts.create')); ?>">انشاء اقفال</a>
    <a class="btn btn-custom-primary" href="<?php echo e(route('daily.reseller.accounts_transactions.index')); ?>">التحويلات
    </a>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>رقم العملية</th>
                    <th>من</th>
                    <th>الى</th>
                    <th>المبلغ المفترض</th>
                    <th>المبلغ الفعلي</th>
                    <th>الفرق</th>
                </tr>
                </thead>

                <?php $__currentLoopData = $managerCloseAccountList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tbody>
                    
                    <tr class="">
                        <td>CSH-<?php echo e($transaction->id); ?></td>
                        <td><?php echo e($transaction->from); ?></td>
                        <td><?php echo e($transaction->to); ?></td>
                        <td><?php echo e(money_format("%i",$transaction->amount)); ?></td>
                        <?php if($transaction->shortage_amount<0): ?>
                            <td><?php echo e(money_format("%i",($transaction->amount - abs($transaction->shortage_amount )))); ?></td>

                        <?php else: ?>
                            <td><?php echo e(money_format("%i",($transaction->amount + abs( $transaction->shortage_amount )))); ?></td>

                        <?php endif; ?>
                        <td>
                            <?php if($transaction->shortage_amount>0): ?>
                                <span class="text-green"><?php echo e(money_format("%i",$transaction->shortage_amount)); ?></span>
                            <?php elseif($transaction->shortage_amount<0): ?>
                                <span class="text-danger"><?php echo e(money_format("%i",$transaction->shortage_amount)); ?></span>
                            <?php else: ?>
                                <?php echo e(money_format("%i",$transaction->shortage_amount)); ?>


                            <?php endif; ?>
                        </td>
                    </tr>

                    

                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    

                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    

                    

                    

                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    
                    </tbody>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php echo e($managerCloseAccountList->links()); ?>

            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/reseller_daily/account_close_list.blade.php ENDPATH**/ ?>