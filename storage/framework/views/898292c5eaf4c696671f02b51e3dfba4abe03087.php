<?php $__env->startSection('title', __('sidebar.chart_of_accounts')); ?>

<?php $__env->startSection('page_css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("create chart")): ?>
        <a class="btn btn-custom-primary" href="<?php echo e(route('accounts.create')); ?>">اضافة حساب</a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <accounting-chart-of-accounts-list-component
            :can-edit-account='<?php echo json_encode(auth()->user()->can('edit chart'), 15, 512) ?>'
            :accounts='<?php echo json_encode($accounts, 15, 512) ?>'></accounting-chart-of-accounts-list-component>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/charts/index.blade.php ENDPATH**/ ?>