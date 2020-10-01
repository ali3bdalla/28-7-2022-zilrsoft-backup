<?php $__env->startSection('title',__('pages/invoice.create')); ?>



<?php $__env->startSection('content'); ?>
    <accounting-sales-create-component
            :cloning='true'
            :quotation='<?php echo json_encode($quotation, 15, 512) ?>'
            :can-create-item="<?php echo e(auth()->user()->canDo('create item')); ?>"
            :can-view-items="<?php echo e(auth()->user()->canDo('view item')); ?>"
            :expenses='<?php echo json_encode($expenses, 15, 512) ?>'
            :clients='<?php echo json_encode($clients, 15, 512) ?>'
            :gateways='<?php echo json_encode($gateways, 15, 512) ?>'
            :salesmen='<?php echo json_encode($salesmen, 15, 512) ?>'
            :creator='<?php echo json_encode(auth()->user()->load('department', 'branch', 'user')) ?>'
    ></accounting-sales-create-component>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/quotations/to_sale.blade.php ENDPATH**/ ?>