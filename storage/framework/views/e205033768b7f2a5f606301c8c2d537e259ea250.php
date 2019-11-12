<?php $__env->startSection('title',"انشاء حساب مالي للمنشأة"); ?>
<?php $__env->startSection('desctipion',__('pages/settings.payments_create')); ?>
<?php $__env->startSection('route',route('management.gateways.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="box">
        <add-new-payment-account-component :organization_gateways='<?php echo json_encode($organization_gateways, 15, 512) ?>' :banks='<?php echo json_encode($banks, 15, 512) ?>'>
        </add-new-payment-account-component>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/settings/payment_account_create.blade.php ENDPATH**/ ?>