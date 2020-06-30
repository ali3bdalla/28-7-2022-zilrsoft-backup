<?php $__env->startSection('title',__('pages/items.create')); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>

    <accounting-items-create-component
            :can-create-category="<?php echo e(auth()->user()->canDo('create category')); ?>"
            :can-create-filter="<?php echo e(auth()->user()->canDo('create filter')); ?>"
            :can-edit-filter="<?php echo e(auth()->user()->canDo('update filter')); ?>"
            :vendors='<?php echo json_encode($vendors, 15, 512) ?>'
            :categories='<?php echo json_encode($categories, 15, 512) ?>'
            :is-cloned="false"
    >
    </accounting-items-create-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/accounting/items/create.blade.php ENDPATH**/ ?>