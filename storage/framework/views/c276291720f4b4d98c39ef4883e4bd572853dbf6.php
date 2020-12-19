<?php $__env->startSection('title',__('sidebar.filters')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("create filter")): ?>
        <a href="<?php echo e(route('accounting.filters.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/filters.create')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>


    <accounting-filters-datatable-component
            :can-edit="<?php echo e(auth()->user()->canDo('edit filter')); ?>"
            :can-create="<?php echo e(auth()->user()->canDo('create filter')); ?>"
            :can-delete="<?php echo e(auth()->user()->canDo('delete filter')); ?>">


    </accounting-filters-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/accounting/filters/index.blade.php ENDPATH**/ ?>