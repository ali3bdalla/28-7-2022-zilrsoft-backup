<?php $__env->startSection('title',__('sidebar.transactions')); ?>
<?php $__env->startSection('desctipion','sales list'); ?>
<?php $__env->startSection('route',route('management.sales.index')); ?>



<?php $__env->startSection('page_css'); ?>
    <style>
        th {
            text-align: center !important;
        }

        .dropdown-menu {
            transform: translate3d(54px, 41px, 0px) !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="box">
        <div class="card-body">
            <a href="<?php echo e(route("management.transactions.create")); ?>" class="button is-info pull-right"><i class='fa
                    fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/transactions.create')); ?></a>
            <br>
            <br>

        </div>
    </div>

    <div class="card">
        <?php if(!empty($transactions)): ?>
            <table class="table is-bordered table-bordered" style="direction: ltr">

                <thead>
                <th>الدائن</th>
                <th>المدين</th>
                <th>اسم الحساب</th>
                <th> شرح</th>
                <th>رقم القيد</th>
                <th>التاريخ</th>
                </thead>


                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tbody>
                    <?php $__currentLoopData = $transaction->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $real_transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="border:none">
                            <?php if($real_transaction['debitable_type']!=""): ?>
                                <th></th>
                                <th><?php echo e($real_transaction['amount']); ?></th>
                                <th><?php echo e($real_transaction->debitable->locale_name); ?> </th>

                            <?php else: ?>
                                <th><?php echo e($real_transaction['amount']); ?></th>
                                <th></th>
                                <th><?php echo e($real_transaction->creditable->locale_name); ?> </th>
                            <?php endif; ?>


                            <?php if($index==0): ?>

                                <th width="20%" style="vertical-align: inherit;" rowspan="<?php echo e(count
                                ($transaction->transactions)); ?>"> <?php echo e($transaction['description']); ?></th>
                                <th width="10%" style="vertical-align: inherit;"
                                    rowspan="<?php echo e(count($transaction->transactions)); ?>"><?php echo e($transaction['id']); ?></th>
                                <th width="15%" style="vertical-align: inherit;"  class="datedirection" rowspan="<?php echo e(count
                                ($transaction->transactions)); ?>"><?php echo e($transaction['created_at']); ?></th>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                    <tr>
                        <th><?php echo e($transaction['amount']); ?></th>
                        <th><?php echo e($transaction['amount']); ?></th>
                        <th></th>
                        <th></th>
                        <th></th>

                    </tr>
                    </tbody>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


            </table>
        <?php endif; ?>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/transactions/index.blade.php ENDPATH**/ ?>