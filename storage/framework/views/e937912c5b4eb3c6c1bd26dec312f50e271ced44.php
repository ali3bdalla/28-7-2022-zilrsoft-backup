<?php $__env->startSection('title',$serial->serial); ?>
<?php $__env->startSection('desctipion',__('sidebar.serial_history')); ?>
<?php $__env->startSection('route',route('management.serial_history.index')); ?>


<?php $__env->startSection('content'); ?>

    <div class="box">
        <?php if(!empty($serial->histories)): ?>
            <div class="table-container">
                <table class="table table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center">date</th>
                        <th class="text-center">event</th>
                        <th class="text-center">user</th>
                        <th class="text-center">creator</th>
                        <th class="text-center">invoice</th>

                    </tr>
                    </thead>

                    <tbody>
                    <?php $__currentLoopData = $serial->histories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $history): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($history->created_at); ?></td>
                            <td><?php echo e($history->event); ?></td>
                            <td><?php echo e($history->user->name); ?></td>
                            <td><?php echo e($history->creator->name); ?></td>
                            <?php if(in_array($history->event,['sale','r_sale'])): ?>
                                <td><a href="<?php echo e(route('management.sales.show',$history->invoice->sale->id)); ?>">
                                        <?php echo e($history->invoice->sale->prefix); ?><?php echo e($history->invoice->sale->id); ?>

                                    </a> </td>
                            <?php else: ?>
                                <td><a href="<?php echo e(route('management.purchases.show',$history->invoice->purchase->id)); ?>">
                                        <?php echo e($history->invoice->purchase->prefix); ?><?php echo e($history->invoice->purchase->id); ?>

                                    </a> </td>
                            <?php endif; ?>



                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/serials/show.blade.php ENDPATH**/ ?>