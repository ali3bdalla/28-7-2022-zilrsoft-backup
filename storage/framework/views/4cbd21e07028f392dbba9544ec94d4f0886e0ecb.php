<?php $__env->startSection('title',$account->locale_name); ?>




<?php $__env->startSection('content'); ?>

    <accounting-global-transactions-list-component
            :account='<?php echo json_encode($account, 15, 512) ?>'>

    </accounting-global-transactions-list-component>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/charts/transactions/v2/index.blade.php ENDPATH**/ ?>