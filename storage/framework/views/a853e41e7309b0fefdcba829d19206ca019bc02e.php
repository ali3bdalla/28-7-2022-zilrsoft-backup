<?php $__env->startSection('title',__('pages/transactions.create')); ?>
<?php $__env->startSection('route',route('management.transactions.index')); ?>



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
<create-transaction-form-component :accounts='<?php echo json_encode($accounts, 15, 512) ?>'></create-transaction-form-component>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/transactions/create.blade.php ENDPATH**/ ?>