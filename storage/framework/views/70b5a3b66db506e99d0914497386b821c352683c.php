<?php $__env->startSection('title',__('sidebar.adjust_stock')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("manage inventory")): ?>
        <a href="<?php echo e(route('accounting.inventories.adjust_stock.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> اضافة بيان
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>

    <accounting-adjust-stock-datatable-component
            :is-deleted="1"
            :can-delete="<?php echo e(auth()->user()->canDo("manage inventory")); ?>"
            :can-manage="<?php echo e(auth()->user()->canDo("manage managers")); ?>"
    >


    </accounting-adjust-stock-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/inventories/adjust_stock/index.blade.php ENDPATH**/ ?>