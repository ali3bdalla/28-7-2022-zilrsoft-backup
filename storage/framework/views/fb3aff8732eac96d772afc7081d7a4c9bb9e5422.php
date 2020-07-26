<?php $__env->startSection('title','تقرير'); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <accounting-account-report-component :accounts='<?php echo json_encode($accounts, 15, 512) ?>'></accounting-account-report-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/accounting/charts/reports/index.blade.php ENDPATH**/ ?>