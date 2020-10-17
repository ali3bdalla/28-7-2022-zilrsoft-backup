<?php $__env->startSection('title',"تسوية المخزون"); ?>
<?php $__env->startSection('buttons'); ?>





<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>

    <accounting-adjust-stock-datatable-component
            :is-deleted="0"
            :can-delete="<?php echo e(auth()->user()->canDo("manage inventory")); ?>"
            :can-manage="<?php echo e(auth()->user()->canDo("manage managers")); ?>"
    >


    </accounting-adjust-stock-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/inventories/adjust_stock/inventory_reconciliation.blade.php ENDPATH**/ ?>