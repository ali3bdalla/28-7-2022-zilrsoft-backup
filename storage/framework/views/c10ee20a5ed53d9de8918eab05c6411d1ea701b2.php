<?php $__env->startSection('title',__('sidebar.account_close')); ?>






<?php $__env->startSection("content"); ?>
    <accounting-period-account-close-component
            :period-sales-amount='<?php echo json_encode(roundMoney($periodSalesAmount), 15, 512) ?>'
            :last-remaining-transfer='<?php echo json_encode(roundMoney($lastRemainingTransferAmount), 15, 512) ?>'
            :gateways='<?php echo json_encode($gateways, 15, 512) ?>'
    ></accounting-period-account-close-component>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/reseller_daily/account_close.blade.php ENDPATH**/ ?>