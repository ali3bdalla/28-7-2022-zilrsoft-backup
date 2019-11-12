<?php $__env->startSection('title'); ?>
    <?php if($type=='all'): ?>
        <?php echo e(__('sidebar.vouchers')); ?>

    <?php elseif($type=='receipt'): ?>
        <?php echo e(__('pages/payments.receipts')); ?>

    <?php else: ?>
        <?php echo e(__('pages/payments.payments')); ?>

    <?php endif; ?>

    <?php $__env->stopSection(); ?>
<?php $__env->startSection('desctipion',__('pages/payments.description')); ?>
<?php $__env->startSection('route',route('management.payments.index')); ?>


<?php $__env->startSection('content'); ?>



    <div class="box">
        <div class="card-body">
            <div class="pull-right">
            <?php if($type=='all'): ?>
                    <a href="<?php echo e(route('management.payments.create_payment')); ?>" class="button is-info "><i class='fa
            fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/payments.create_payment')); ?></a>
                    &nbsp;&nbsp;
                    <a href="<?php echo e(route('management.payments.create_receipt')); ?>" class="button is-warning "><i class='fa
            fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/payments.create_receipt')); ?></a>

            <?php elseif($type=='receipt'): ?>

                    <a href="<?php echo e(route('management.payments.create_receipt')); ?>" class="button is-warning "><i class='fa
            fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/payments.create_receipt')); ?></a>

            <?php else: ?>

                    <a href="<?php echo e(route('management.payments.create_payment')); ?>" class="button is-info "><i class='fa
            fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/payments.create_payment')); ?></a>

            <?php endif; ?>
            </div>



            <br>
            <br>













        </div>


    </div>
        <div class="box">
            <?php if(isset($payments)): ?>
                <table class="table is-borderd table-bordered text-center">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th><?php echo e(__('pages/payments.user_name')); ?></th>
                        <th><?php echo e(__('reusable.date')); ?></th>
                        <th><?php echo e(__('reusable.creator')); ?></th>
                        <th><?php echo e(__('pages/payments.total_amount')); ?></th>
                        <th><?php echo e(__('pages/payments.gateway')); ?></th>
                        <th><?php echo e(__('reusable.manage')); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($payment->id); ?></td>
                            <td><?php echo e($payment->user->name); ?></td>
                            <td class="datedirection"><?php echo e($payment->created_at); ?></td>
                            <td><?php echo e($payment->creator->name); ?> </td>

                            <td><?php echo e($payment->amount); ?></td>
                            <td><?php echo e($payment->gateway->name); ?></td>
                            <td >


                                <div class="dropdown">
                                    <button class="button is-primary  btn-xs" type="button" id="optionsMenu<?php echo e($payment->id); ?>"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-cogs"></i> &nbsp; <?php echo e(__('reusable.manage')); ?>

                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="optionsMenu<?php echo e($payment->id); ?>">
                                        <a class="dropdown-item"
                                           href="<?php echo e(route('management.payments.show',$payment->id)); ?>"><i
                                                    class="fa fa-eye"></i> &nbsp; <?php echo e(__('reusable.view')); ?></a>
                                        <a class="dropdown-item" href="<?php echo e(route('management.payments.edit',$payment->id)); ?>"><i class="fa fa-print"></i> <?php echo e(__('reusable.print')); ?></a>

                                    </div>
                                </div>


                            </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($payments->links()); ?>

            <?php endif; ?>

        </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/payments/index.blade.php ENDPATH**/ ?>