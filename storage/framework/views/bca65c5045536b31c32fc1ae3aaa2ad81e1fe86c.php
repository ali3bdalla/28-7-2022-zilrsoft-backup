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

        <div class="panel-heading">

        </div>

        <div class="panel-body">
            <?php if(!empty($transactions)): ?>
                <table class="table table-bordered table-bordered" style="direction: ltr">

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

                        <?php if($transaction['invoice_id']>=1 && !empty($transaction->invoice)): ?>

                            <?php if(!empty($transaction->invoice->sale)): ?>
						   <?php $sale = $transaction->invoice->sale;$invoice_transactions =
							   $transaction->invoice->transactions()->where('description','!=','client_balance')->get
							   ();?>
                                 <?php if ($__env->exists('accounting.transactions.sale')) echo $__env->make('accounting.transactions.sale', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <?php elseif(!empty($transaction->invoice->purchase)): ?>
						   <?php $purchase = $transaction->invoice->purchase;$invoice_transactions =
							   $transaction->invoice->transactions()->where('description','!=','vendor_balance')->get
							   ();?>
                                 <?php if ($__env->exists('accounting.transactions.purchase')) echo $__env->make('accounting.transactions.purchase', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <?php endif; ?>

                        <?php else: ?>

                            <?php $__currentLoopData = $transaction->transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $real_transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr style="border:none">


                                    <?php if($real_transaction['debitable_type']!=""): ?>
                                        <th></th>
                                        <th><?php echo e($real_transaction['amount']); ?></th>
                                        <th><?php if(!empty($real_transaction->debitable)): ?> <?php echo e($real_transaction->debitable->locale_name); ?> <?php endif; ?> </th>

                                    <?php else: ?>
                                        <th><?php echo e($real_transaction['amount']); ?></th>
                                        <th></th>
                                        <th><?php if(!empty($real_transaction->creditable)): ?> <?php echo e($real_transaction->creditable->locale_name); ?> <?php endif; ?> </th>
                                    <?php endif; ?>


                                    <?php if($index==0): ?>

                                        <th width="20%" style="vertical-align: inherit;" rowspan="<?php echo e(count
                                ($transaction->transactions)); ?>"> <?php echo e($transaction['description']); ?></th>
                                        <th width="10%" style="vertical-align: inherit;"
                                            rowspan="<?php echo e(count($transaction->transactions)); ?>"><a href="<?php echo e(route('accounting.transactions.show',$transaction->id)); ?>"><?php echo e($transaction['id']); ?></a></th>
                                        <th width="15%" style="vertical-align: inherit;" class="datedirection" rowspan="<?php echo e(count
                                ($transaction->transactions)); ?>"><?php echo e($transaction['created_at']); ?></th>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            <tr style="background-color: #eeeeee">
                                <th><?php echo e(money_format("%i",$transaction['amount'])); ?></th>
                                <th><?php echo e(money_format("%i",$transaction['amount'])); ?></th>
                                <th>المجموع</th>
                                <th></th>
                                <th></th>
                                <th></th>

                            </tr>
                        </tbody>

                        <?php endif; ?>


                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </table>


                <?php echo e($transactions->links()); ?>

            <?php endif; ?>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/accounting/transactions/index.blade.php ENDPATH**/ ?>