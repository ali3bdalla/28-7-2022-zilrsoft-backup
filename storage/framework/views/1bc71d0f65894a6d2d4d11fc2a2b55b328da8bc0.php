<?php $__env->startSection('content'); ?>
    <?php
        $totalCreditAmount = 0;
        $totalDebitAmount = 0;
        $totalCreditBalance = 0;
        $totalDebitBalance = 0
    ?>
    <div class="table">
        <table class="table table-bordered bg-white" id="myTable" class="display" style="width:100%">
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

            <?php
                $padding = 20
            ?>
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <tbody>
                <tr>
                    <td></td>

                    <td class="text-bold text-right p-3" style="text-align: right !important;font-size: 25px"><?php echo e($account->locale_name); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                <?php if ($__env->exists('accounting::trial_balance.table_cell',['account' => $account])) echo $__env->make('accounting::trial_balance.table_cell',['account' => $account], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                </tbody>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <thead style="background-color:black;color:white">
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


                <th colspan="2"><?php echo e(money_format('%i',$totalDebitBalance - $totalCreditBalance)); ?></th>

            </tr>

            </thead>
        </table>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('page_css'); ?>
    <link href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.js" defer></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js" defer></script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('page_js'); ?>

    <script>
        $(document).ready(function () {
            $('#myTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'colvis',
                    'excel',
                    'print'
                ]
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/Modules/Accounting/Resources/views/trial_balance/index2.blade.php ENDPATH**/ ?>