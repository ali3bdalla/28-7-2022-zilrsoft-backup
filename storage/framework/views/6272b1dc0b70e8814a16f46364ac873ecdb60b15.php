<?php $__env->startSection('title',__('pages/invoice.create')); ?>





<?php $__env->startSection("content"); ?>

    <accounting-beginning-create-component
    :user='<?php echo json_encode($user, 15, 512) ?>'
    :creator='<?php echo json_encode($creator, 15, 512) ?>'
    :can-create-item="<?php echo e(auth()->user()->canDo('create item')); ?>"
    :can-view-items="<?php echo e(auth()->user()->canDo('view item')); ?>"
    >


    </accounting-beginning-create-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/inventories/beginning/create.blade.php ENDPATH**/ ?>