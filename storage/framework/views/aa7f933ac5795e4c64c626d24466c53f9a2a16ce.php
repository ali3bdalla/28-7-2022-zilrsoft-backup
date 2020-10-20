<?php $__env->startSection('title',__('sidebar.quotations')); ?>
<?php $__env->startSection('buttons'); ?>
    <a href="<?php echo e(route('accounting.quotations.create')); ?>" class="btn btn-custom-primary">
        <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/invoice.create')); ?>

    </a>






<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_css'); ?>
    <style>
        .navbar {
            background-color: #b8b83a !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection("content"); ?>

    <accounting-sales-datatable-component
            :only-quotations="true"
            :creators='<?php echo json_encode($creators, 15, 512) ?>'
            :vendors='<?php echo json_encode($clients, 15, 512) ?>'
            :creator='<?php echo json_encode(auth()->user(), 15, 512) ?>'
    >


    </accounting-sales-datatable-component>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/vhosts/zilrsoft.com/resources/views/accounting/quotations/index.blade.php ENDPATH**/ ?>