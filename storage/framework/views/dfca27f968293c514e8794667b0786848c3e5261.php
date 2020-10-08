<?php $__env->startSection('title',__('sidebar.services_quotations')); ?>

<?php $__env->startSection('content'); ?>
    <accounting-quotations-services-create-component
            :can-create-item="<?php echo e(auth()->user()->canDo('create item')); ?>"
            :can-view-items="<?php echo e(auth()->user()->canDo('view item')); ?>"
            :services='<?php echo json_encode($services, 15, 512) ?>'
            :clients='<?php echo json_encode($clients, 15, 512) ?>'
            :gateways='<?php echo json_encode($gateways, 15, 512) ?>'
            :salesmen='<?php echo json_encode($salesmen, 15, 512) ?>'
            :creator='<?php echo json_encode(auth()->user()->load('department', 'branch', 'user')) ?>'
    >
    </accounting-quotations-services-create-component>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/sales/create_draft_service.blade.php ENDPATH**/ ?>