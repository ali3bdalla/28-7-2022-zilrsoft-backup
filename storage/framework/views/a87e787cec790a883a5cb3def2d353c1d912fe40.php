<?php $__env->startSection('title',__('sidebar.sales')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("create sale")): ?>
        <a href="<?php echo e(route('accounting.sales.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/invoice.create')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>

    <accounting-sales-datatable-component
            :creators='<?php echo json_encode($creators, 15, 512) ?>'
            :vendors='<?php echo json_encode($clients, 15, 512) ?>'
            :can-edit="<?php echo e(auth()->user()->canDo('edit sale')); ?>"
    >


    </accounting-sales-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/sales/index.blade.php ENDPATH**/ ?>