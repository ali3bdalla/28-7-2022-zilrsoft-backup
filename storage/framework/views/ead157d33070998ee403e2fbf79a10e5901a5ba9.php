<?php $__env->startSection('title',__('sidebar.kits')); ?>
<?php $__env->startSection('desctipion','items'); ?>
<?php $__env->startSection('route',route('management.items.index')); ?>

<?php $__env->startSection('content'); ?>


    <div class="card">
        <div class="">


            <?php if(isset($serials)): ?>
                <table class="table is-borderd table-bordered text-center">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>السيريال</th>
                        <th>الحالة</th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $serials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($serial->id); ?></td>
                            <td><?php echo e($serial->serial); ?></td>
                            <td><?php echo e($serial->current_status); ?> </td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($serials->links()); ?>

            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/items/serials.blade.php ENDPATH**/ ?>