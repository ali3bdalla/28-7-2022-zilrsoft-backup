<?php $__env->startSection('title',__('sidebar.users')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("create identity")): ?>
        <a href="<?php echo e(route('accounting.identities.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/users.create')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>


    <accounting-identities-datatable-component
            :can-edit="<?php echo e(auth()->user()->canDo('view identity')); ?>"
            :can-create="<?php echo e(auth()->user()->canDo('create identity')); ?>"
            :can-delete="<?php echo e(auth()->user()->canDo('view identity')); ?>">


    </accounting-identities-datatable-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/identities/index.blade.php ENDPATH**/ ?>