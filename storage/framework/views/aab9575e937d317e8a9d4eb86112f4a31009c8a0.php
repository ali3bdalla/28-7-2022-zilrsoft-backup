<?php $__env->startSection('title',__('sidebar.financial_statements')); ?>
<?php $__env->startSection('route',route('management.financial_statements.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="box">
        <div class="panel">
            <table class="table table-bordered is-bordered text-center">
                <thead>
                <tr>
                    <th></th>
                    <th colspan="2">المجاميع</th>
                    <th colspan="2">الارصدة</th>
                </tr>


                <tr>
                    <th>اسم الحساب</th>
                    <th>مدين</th>
                    <th>دائن</th>
                    <th>مدين</th>
                    <th>دائن</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td width="20%"><?php echo e($account['locale_name']); ?></td>
                        <td><?php echo e(money_format("%i", $account['total_debit'])); ?></td>
                        <td><?php echo e(money_format("%i", $account['total_credit'])); ?></td>
                        <td><?php echo e(money_format("%i", $account['balance_debit'])); ?></td>
                        <td><?php echo e(money_format("%i", $account['balance_credit'])); ?></td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/financial_statements/trail_balance.blade.php ENDPATH**/ ?>