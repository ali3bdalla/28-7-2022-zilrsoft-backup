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
                
                <th>المدين</th>
                <th>الدائن</th>


                </thead>
                <body>
                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    
                    
                    
                    
                    
                    

                    
                    
                    
                    
                    
                    
                    
                    
                    


                    
                    
                    <tr>

                        <th><?php echo e($transaction->created_at); ?></th>
                        <th><?php echo e($transaction->container_id); ?></th>
                        <th>

                            <?php if($transaction->invoice_id != null): ?>
                                <?php echo e($transaction->invoice->invoice_number); ?>

                            <?php else: ?>
                                <?php echo e($transaction->description); ?>

                            <?php endif; ?>
                        </th>

                        <th> <?php if($transaction->type =='debit'): ?><?php echo e(roundMoney($transaction->amount)); ?><?php else: ?> 0 <?php endif; ?></th>
                        <th> <?php if($transaction->type =='credit'): ?><?php echo e(roundMoney($transaction->amount)); ?><?php else: ?> 0 <?php endif; ?></th>



                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </body>
            </table>
            <?php echo e($transactions->links()); ?>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting_module/entities/index.blade.php ENDPATH**/ ?>