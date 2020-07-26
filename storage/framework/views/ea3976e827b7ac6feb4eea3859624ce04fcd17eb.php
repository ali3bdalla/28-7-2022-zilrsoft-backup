<?php $__env->startSection('title',__('pages/vouchers.create_' . $voucher_type)); ?>



<?php $__env->startSection('page_css'); ?>
    <script defer>
        window.translator = `<?php echo json_encode(trans('pages/vouchers'), 15, 512) ?>`
    </script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>


    <accounting-vouchers-create-component
            :payment_type='<?php echo json_encode($voucher_type, 15, 512) ?>'
            :accounts='<?php echo json_encode($accounts, 15, 512) ?>'
            :voucher_types='<?php echo json_encode($voucher_types, 15, 512) ?>'
            :users='<?php echo json_encode($users, 15, 512) ?>'
    >
    </accounting-vouchers-create-component>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/vouchers/create.blade.php ENDPATH**/ ?>