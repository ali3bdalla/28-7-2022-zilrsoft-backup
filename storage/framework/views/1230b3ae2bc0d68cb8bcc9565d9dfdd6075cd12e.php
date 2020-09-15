<?php $__env->startSection('title',__('pages/invoice.return')); ?>









<?php $__env->startSection('page_css'); ?>
    <style>
        .navbar {
            background-color: green !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <accounting-purchases-return-component
            :gateways='<?php echo json_encode($gateways, 15, 512) ?>'
            :creator='<?php echo json_encode($invoice->creator, 15, 512) ?>'
            :items='<?php echo json_encode($items, 15, 512) ?>'
            :invoice='<?php echo json_encode($invoice->load('department', 'branch'), 512) ?>'
            :purchase='<?php echo json_encode($purchase->load('receiver', 'vendor'), 512) ?>'>

    </accounting-purchases-return-component>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/purchases/edit.blade.php ENDPATH**/ ?>