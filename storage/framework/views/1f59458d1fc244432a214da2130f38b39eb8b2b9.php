<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = `<?php echo json_encode(trans('pages/items'), 15, 512) ?>`
    </script>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("content"); ?>
        <accounting-item-transactions-component :item='<?php echo json_encode($item, 15, 512) ?>'></accounting-item-transactions-component>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/items/transactions.blade.php ENDPATH**/ ?>