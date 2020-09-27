<?php $__env->startSection('title',__('pages/items.view_serials') . " | " . $item->locale_name ); ?>


<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>
    <div class="panel">
        <?php if(isset($serials)): ?>
            <table class="table table-bordered text-center table-primary">
                <thead class="panel-heading">
                <tr>
                    <th>#</th>
                    <th>السيريال</th>

                    <th>الحالة</th>

                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $serials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $serial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->index + 1); ?></td>
                        <td><?php echo e($serial->serial); ?></td>




                        <td><?php echo e(trans('pages/items.' . $serial->status)); ?> </td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php echo e($serials->links()); ?>

        <?php endif; ?>
    </div>

<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/items/view_serials.blade.php ENDPATH**/ ?>