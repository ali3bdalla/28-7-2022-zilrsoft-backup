<?php $__env->startSection('title',__('pages/users.managers')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("manage managers")): ?>
        <a href="<?php echo e(route('accounting.managers.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/users.create_manager')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection("content"); ?>
    <accounting-managers-datatable-component
            :can-edit="<?php echo e(auth()->user()->canDo('manage managers')); ?>"
            :can-create="<?php echo e(auth()->user()->canDo('manage managers')); ?>"
            :branches='<?php echo json_encode($branches, 15, 512) ?>'
            :can-delete="<?php echo e(auth()->user()->canDo('manage managers')); ?>">


    </accounting-managers-datatable-component>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/managers/show.blade.php ENDPATH**/ ?>