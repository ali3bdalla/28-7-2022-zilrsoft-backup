<?php $__env->startSection('title',__('sidebar.purchases')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("create purchase")): ?>
        <a href="<?php echo e(route('accounting.purchases.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/invoice.create')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>

    <accounting-purchases-datatable-component>


    </accounting-purchases-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/purchases/index.blade.php ENDPATH**/ ?>