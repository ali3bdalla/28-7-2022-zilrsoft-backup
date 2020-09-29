<?php $__env->startSection('title',__('sidebar.transactions')); ?>


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


<?php $__env->startSection('buttons'); ?>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create transaction')): ?>
        <a href="<?php echo e(route("accounting.transactions.create")); ?>" class="btn btn-custom-primary"><i class='fa
                    fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/transactions.create')); ?></a>
        <br>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="panel">
    <div class="panel-body">
            <table class="table table-bordered table-bordered" style="">
                <thead>
                    <th>التاريخ</th>
                    <th>رقم القيد</th>
                    <th> شرح</th>
                    <th>اسم الحساب</th>
                    <th>المدين</th>
                <th>الدائن</th>
              
                </thead>
            
                <body>
                    <?php $__currentLoopData = $entities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php 
                        $totalCredit = 0;
                        $totalDebit = 0;
                    ?>
                        <?php $__currentLoopData = $entity->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="border:none">
                            <th><?php echo e($entity->created_at); ?></th>
                            <th><?php echo e($entity->id); ?></th>
                            <th><?php echo e($entity->description); ?></th>
                            <th><?php echo e($transaction->account_name); ?></th>

                            <?php if($transaction->type=="credit"): ?>
                                <?php 
                                    $totalCredit+= (float)$transaction['amount'];
                                ?>
                                <th></th>
                                <th><?php echo e($transaction['amount']); ?></th>
                            <?php else: ?>
                                <?php 
                                    $totalDebit+= (float)$transaction['amount'];
                                ?>
                                <th><?php echo e($transaction['amount']); ?></th>
                                <th></th>
                            <?php endif; ?>


                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr style="background-color: #eeeeee">
                           
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>المجموع</th>

                            <th><?php echo e(money_format("%i",$totalDebit)); ?></th>
                            <th><?php echo e(money_format("%i",$totalCredit)); ?></th>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </body>
            </table>
            <?php echo e($entities->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/transactions/index.blade.php ENDPATH**/ ?>