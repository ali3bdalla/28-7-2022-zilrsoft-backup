<?php $__env->startSection('content'); ?>

    <div class="table">
        <table class="table table-bordered bg-white" id="myTable" class="display" style="width:100%">
            <thead>
            <tr>
                <td></td>
                <td colspan="2">المجاميع</td>
                <td colspan="2">الارصدة</td>

            </tr>
            <tr>
                <td>اسم الحساب</td>
                <td>مدين</td>
                <td>دائن</td>
                <td>مدين</td>
                <td>دائن</td>

            </tr>
            </thead>

            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tbody>
                <tr>

                    <td class="text-bold text-right p-3" style="text-align: right !important;font-size: 25px"><?php echo e($account->locale_name); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                <?php $__currentLoopData = $account['mainAccountChildren']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tbody style="margin-right: 5px !important;" class="table-body-child">
                    <tr>
                        <td colspan="" class="text-bold"
                            style="padding-right:40px;text-align: right !important;"><a href="<?php echo e(route('accounting.accounts.show',$account2->id)); ?>"><?php echo e($account2->locale_name); ?></a> </td>
                        <td><?php echo e($account2['debit_amount']); ?></td>
                        <td><?php echo e($account2['credit_amount']); ?></td>
                        <td><?php echo e($account2['debit_balance']); ?></td>
                        <td><?php echo e($account2['credit_balance']); ?></td>

                    </tr>
                    </tbody>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </tbody>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <thead style="background-color:black;color:white">
                <tr>
                    <th></th>
                    <th><?php echo e(displayMoney($totalDebitAmount)); ?></th>
                    <th><?php echo e(displayMoney($totalCreditAmount)); ?></th>
                    <th><?php echo e(displayMoney($totalDebitBalance)); ?></th>
                    <th><?php echo e(displayMoney($totalCreditBalance)); ?></th>
                </tr>



                </thead>
        </table>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting_module/financial_statements/trail_balance.blade.php ENDPATH**/ ?>