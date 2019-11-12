<?php $__env->startSection('title',__('pages/payments.create_payment')); ?>
<?php $__env->startSection('desctipion',''); ?>
<?php $__env->startSection('route',route('management.payments.index')); ?>



<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = `<?php echo json_encode(trans('pages/payments'), 15, 512) ?>`
    </script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>


    



            <create-payment-form-component
                    :organization_gateways='<?php echo json_encode($organization_gateways, 15, 512) ?>'
                    :organization-banks='<?php echo json_encode($organization_accounts, 15, 512) ?>'
                    :country-banks='<?php echo json_encode($all_banks, 15, 512) ?>'
                    :clients='<?php echo json_encode($clients, 15, 512) ?>'
                    :vendors='<?php echo json_encode($vendors, 15, 512) ?>'

            ></create-payment-form-component>




<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/payments/create_payment.blade.php ENDPATH**/ ?>