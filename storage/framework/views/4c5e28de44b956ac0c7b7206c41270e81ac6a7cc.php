<?php $__env->startSection('title',__('pages/users.create')); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <accounting-identities-create-component
            :banks='<?php echo json_encode($banks, 15, 512) ?>'
            :editing-identity="false"
    ></accounting-identities-create-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/identities/create.blade.php ENDPATH**/ ?>