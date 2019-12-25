<?php $__env->startSection('title',__('sidebar.beginning_inventory')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("manage inventory")): ?>
        <a href="<?php echo e(route('accounting.inventories.beginning.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/invoice.create')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>

    <accounting-beginning-datatable-component>


    </accounting-beginning-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/inventories/beginning/show.blade.php ENDPATH**/ ?>