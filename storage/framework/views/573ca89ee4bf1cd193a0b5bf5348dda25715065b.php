<?php $__env->startSection('content'); ?>
    <div class="table">
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <td></td>
                <td></td>
                <td colspan="2">المجاميع</td>
                <td colspan="2">الارصدة</td>

            </tr>
            <tr>
                <td>#</td>
                <td>اسم الحساب</td>
                <td>مدين</td>
                <td>دائن</td>
                <td>مدين</td>
                <td>دائن</td>

            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($account->id); ?></td>
                <td><?php echo e($account->locale_name); ?></td>
                <td><?php echo e(money_format('%i',$account->debit_amount)); ?></td>
                <td><?php echo e(money_format('%i',$account->credit_amount)); ?></td>
                <td><?php echo e(money_format('%i',$account->debit_balance)); ?></td>
                <td><?php echo e(money_format('%i',$account->credit_balance)); ?></td>

            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <thead>
        <tr>
            <th></th>
            <th></th>
            <th><?php echo e(money_format('%i',$totalDebitAmount)); ?></th>
            <th><?php echo e(money_format('%i',$totalCreditAmount)); ?></th>
            <th><?php echo e(money_format('%i',$totalDebitBalance)); ?></th>
            <th><?php echo e(money_format('%i',$totalCreditBalance)); ?></th>

        </tr>

        <tr>
            <th></th>
            <th></th>
            <th colspan="2"><?php echo e(money_format('%i',$totalDebitAmount - $totalCreditAmount)); ?></th>


            <th colspan="2"><?php echo e(money_format('%i',$totalDebitBalance - $totalCreditBalance)); ?></>

        </tr>

        </thead>
    </table>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/Modules/Accounting/Resources/views/trial_balance/index.blade.php ENDPATH**/ ?>