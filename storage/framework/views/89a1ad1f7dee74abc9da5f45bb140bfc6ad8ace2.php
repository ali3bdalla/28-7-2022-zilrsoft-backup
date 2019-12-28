<?php $__env->startSection('title',__('pages/users.create_manager')); ?>



<?php $__env->startSection("content"); ?>
    <accounting-managers-create-component
            :editing-manager="false"
            :branches='<?php echo json_encode($branches, 15, 512) ?>'
            :gateways='<?php echo json_encode($gateways, 15, 512) ?>'
    >

    </accounting-managers-create-component>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/managers/create.blade.php ENDPATH**/ ?>