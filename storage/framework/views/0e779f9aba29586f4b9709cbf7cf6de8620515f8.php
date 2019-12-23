<?php $__env->startSection('title',__('sidebar.branches')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("manage branches")): ?>
        <a href="<?php echo e(route('accounting.branches.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/branches.create')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>


    <accounting-branches-datatable-component
            :can-edit="<?php echo e(auth()->user()->canDo('manage branches')); ?>"
            :can-create="<?php echo e(auth()->user()->canDo('manage branches')); ?>"
            :can-delete="<?php echo e(auth()->user()->canDo('manage branches')); ?>"
    >

    </accounting-branches-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/branches/index.blade.php ENDPATH**/ ?>