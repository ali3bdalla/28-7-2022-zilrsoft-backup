<?php $__env->startSection('title',__('pages/payments.create_payment')); ?>
<?php $__env->startSection('desctipion',''); ?>
<?php $__env->startSection('route',route('management.payments.index')); ?>



<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = `<?php echo json_encode(trans('pages/payments'), 15, 512) ?>`
    </script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>


    <create-voucher-form-component
            payment_type="payment"
            :accounts='<?php echo json_encode($accounts, 15, 512) ?>'
            :voucher_types='<?php echo json_encode($voucher_types, 15, 512) ?>'
            :users='<?php echo json_encode($users, 15, 512) ?>'
    >

    </create-voucher-form-component>
    
    
    

    


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/payments/create_payment.blade.php ENDPATH**/ ?>