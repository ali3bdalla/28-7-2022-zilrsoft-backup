<?php $__env->startSection('title', __('sidebar.account_close')); ?>

<?php $__env->startSection('buttons'); ?>
    <a class="btn btn-custom-primary" href="<?php echo e(route('accounting.reseller_daily.transfer_amounts')); ?>">انشاء تحويل</a>
    <a class="btn btn-custom-primary" href="<?php echo e(route('accounting.reseller_daily.account_close_list')); ?>">الاقفالات
    </a>



<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="panel">
        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>رقم العملية </th>
                    <th>التاريخ</th>
                    <th>الى</th>
                    <th>المبلغ الاجمالي</th>
                    <th>المبلغ المحول</th>
                    <th>المتبقي</th>
                    <th>الحالة</th>
                </tr>
                </thead>

                <?php $__currentLoopData = $managerCloseAccountList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $total_amount = $transaction->container->transactions()->where("debitable_type","!=","")
			            ->withoutGlobalScope
			            ('pendingTransactionScope')->sum('amount'); ?>
		            <tbody>
                    
                    
                    
                    
                    
                    
                    
                    

                    

                    <tr class="">
                        <td>TRANS-<?php echo e($transaction->creator->id); ?></td>
                        <td><?php echo e($transaction->created_at); ?></td>
                        <td><?php echo e($transaction->receiver->locale_name); ?></td>
                        <td><?php echo e(money_format("%i",$total_amount)); ?></td>

                        <td><?php echo e(money_format("%i",$transaction->amount)); ?></td>
                        <td><?php echo e(money_format("%i",$total_amount - $transaction->amount)); ?></td>
                        <td>
                            <?php if($transaction->is_pending): ?>
                                منتظرة
                            <?php else: ?>
                                مقبولة
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
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/reseller_daily/tranfers_list.blade.php ENDPATH**/ ?>