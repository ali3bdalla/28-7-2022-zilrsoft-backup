<?php $__env->startSection('title',"قيد  - {$entity->id}"); ?>




<?php $__env->startSection('content'); ?>
    <div class="panel">

        <div class="panel panel-body">
            <h4><?php echo e($entity->created_at); ?> - <?php echo e($entity->creator->name); ?></h4>
            <h5>الوصف : <?php echo e($entity->description); ?></h5>

        </div>

        <table class="table table-bordered table-sorted">
            <thead>
            <tr>
                <td>الحساب</td>
                <td>مدين</td>
                <td>دائن</td>
                <td>الوصف</td>
            </tr>

            </thead>
            <tbody>
            <?php $__currentLoopData = $entity->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($transaction->account_name); ?></td>
                    <?php if($transaction->type == 'debit'): ?>
                        <td><?php echo e(displayMoney($transaction->amount)); ?></td>
                        <td>0</td>
                    <?php else: ?>
                        <td>0</td>
                        <td><?php echo e(displayMoney($transaction->amount)); ?></td>
                    <?php endif; ?>

                    <td><?php echo e($transaction->description); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <tr class="bg-primary">
                <th></th>
                <th><?php echo e(displayMoney($entity->total_debit_amount)); ?></th>
                <th><?php echo e(displayMoney($entity->total_credit_amount)); ?></th>
                <th></th>
                <th></th>
            </tr>
            </tbody>
        </table>
    </div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting_module/entities/show.blade.php ENDPATH**/ ?>