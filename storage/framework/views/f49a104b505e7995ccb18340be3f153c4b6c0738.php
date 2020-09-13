<?php $__env->startSection('title',$serial->serial . " | " . $serial->item->locale_name . ' (' .$serial->item->barcode . ')'); ?>

<?php $__env->startSection('content'); ?>

    <div class="box">
        <?php if(!empty($serial->histories)): ?>
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center"><?php echo e(trans('pages/items.date')); ?></th>
                        <th class="text-center"><?php echo e(trans('pages/invoice.invoice_type')); ?></th>
                        <th class="text-center"><?php echo e(trans('pages/items.movement.user')); ?></th>
                        <th class="text-center"><?php echo e(trans('reusable.creator')); ?></th>
                        <th class="text-center"><?php echo e(trans('pages/invoice.invoice_number')); ?></th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php $__currentLoopData = $serial->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($history->created_at); ?></td>
                            <td><?php echo e(trans('pages/invoice.' . $history->event)); ?></td>
                            <td><?php echo e($history->user->locale_name); ?></td>
                            <td><?php echo e($history->creator->locale_name); ?></td>
                            <?php if(in_array($history->event,['sale','r_sale'])): ?>
                                <td>
                                    <?php if($history->invoice!=null): ?>
                                        <a href="<?php echo e(route('accounting.sales.show',$history->invoice->id)); ?>">
                                            <?php echo e($history->invoice->title); ?>

                                        </a>
                                    <?php endif; ?>
                                </td>
                            <?php else: ?>
                                <td>
                                    <?php if($history->invoice!=null): ?>
                                        <a href="<?php echo e(route('accounting.purchases.show',$history->invoice->id)); ?>">
                                            <?php echo e($history->invoice->title); ?>

                                        </a>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>


                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/items/serial/show.blade.php ENDPATH**/ ?>