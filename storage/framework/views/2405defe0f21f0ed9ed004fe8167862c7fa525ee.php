<?php $__env->startSection('title',__('pages/invoice.purchase')); ?>



<?php $__env->startSection('content'); ?>
    <accounting-purchases-create-component
            :can-create-item="<?php echo e(auth()->user()->canDo('create item')); ?>"
            :can-view-items="<?php echo e(auth()->user()->canDo('view item')); ?>"
            :expenses='<?php echo json_encode($expenses, 15, 512) ?>'
            :vendors='<?php echo json_encode($vendors, 15, 512) ?>'
            :gateways='<?php echo json_encode($gateways, 15, 512) ?>'
            :receivers='<?php echo json_encode($receivers, 15, 512) ?>'
            :creator='<?php echo json_encode(auth()->user()->with('department', 'branch')->first(), 512) ?>'
    ></accounting-purchases-create-component>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/purchases/create.blade.php ENDPATH**/ ?>