<?php $__env->startSection('title',__('sidebar.vouchers')); ?>



<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create voucher')): ?>
        <a href="<?php echo e(route("vouchers.create")); ?>?voucher_type=payment" class="btn btn-custom-primary"><i
                    class='fa
                    fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/vouchers.create_payment')); ?></a>
        <a href="<?php echo e(route("vouchers.create")); ?>?voucher_type=receipt" class="btn  btn-custom-default"><i
                    class='fa
                    fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/vouchers.create_receipt')); ?></a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <accounting-vouchers-datatable-component
            :identities='<?php echo json_encode($identities, 15, 512) ?>'
            :creators='<?php echo json_encode($creators, 15, 512) ?>'
    >

    </accounting-vouchers-datatable-component>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/vouchers/index.blade.php ENDPATH**/ ?>