<?php $__env->startSection('title',__('sidebar.kits')); ?>
<?php $__env->startSection('buttons'); ?>
    <a href="<?php echo e(route('accounting.kits.create')); ?>" class="btn btn-custom-primary">
        <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/items.create_kit')); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>


    <accounting-kits-datatable-component
            :can-edit="<?php echo e(auth()->user()->canDo('manage kit')); ?>"
            :can-create="<?php echo e(auth()->user()->canDo('manage kit')); ?>"
            :categories='<?php echo json_encode($categories, 15, 512) ?>'
            :creators='<?php echo json_encode($creators, 15, 512) ?>'
            :can-view-accounting="<?php echo e(auth()->user()->canDo('view item transactions')); ?>"
            :can-delete="<?php echo e(auth()->user()->canDo('manage kit')); ?>">
    </accounting-kits-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/accounting/kits/index.blade.php ENDPATH**/ ?>