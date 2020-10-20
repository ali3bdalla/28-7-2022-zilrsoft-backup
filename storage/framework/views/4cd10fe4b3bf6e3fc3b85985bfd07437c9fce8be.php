<?php $__env->startSection('title',__('pages/reseller_daily.transfer_amounts')); ?>






<?php $__env->startSection("content"); ?>
    <accounting-reseller-daily-transfer-amounts-component
            :gateways='<?php echo json_encode($manager_gateways, 15, 512) ?>'
            :to-gateways='<?php echo json_encode($gateways, 15, 512) ?>'
    >

    </accounting-reseller-daily-transfer-amounts-component>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/reseller_daily/transfer_amounts.blade.php ENDPATH**/ ?>