<?php $__env->startSection('title',__('pages/transactions.create')); ?>



<?php $__env->startSection('page_css'); ?>
    <style>
        th {
            text-align: center !important;
        }

        .dropdown-menu {
            transform: translate3d(54px, 41px, 0px) !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <accounting-transactions-create-component
            :accounts='<?php echo json_encode($accounts, 15, 512) ?>'
            :vendors='<?php echo json_encode($vendors, 15, 512) ?>'
            :clients='<?php echo json_encode($clients, 15, 512) ?>'
            :items='<?php echo json_encode($items, 15, 512) ?>'
    >

    </accounting-transactions-create-component>
    

<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/transactions/create.blade.php ENDPATH**/ ?>