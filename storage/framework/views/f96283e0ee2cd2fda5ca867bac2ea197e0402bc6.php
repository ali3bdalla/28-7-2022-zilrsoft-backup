<?php $__env->startSection('title',"قيد  - {$entity->id}"); ?>




<?php $__env->startSection('content'); ?>
    <div class="panel">

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
                <td><?php echo e($transaction->account->name); ?></td>
                <?php if($transaction->type == 'debit'): ?>
                    <td><?php echo e($transaction->amount); ?></td>
                    <td>0</td>
                <?php else: ?>
                    <td>0</td>
                    <td><?php echo e($transaction->amount); ?></td>
                <?php endif; ?>
                
                <td><?php echo e($transaction->description); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
        </table>
    </div>


    <div class="panel panel-body">
        <h3><?php echo e($entity->description); ?></h3>
        <h4><?php echo e($entity->created_at); ?></h4>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting_module/entities/show.blade.php ENDPATH**/ ?>