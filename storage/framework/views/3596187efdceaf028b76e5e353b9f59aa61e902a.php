<?php $__env->startSection('title',__('pages/users.edit') . " | ". $identity->name); ?>



<?php $__env->startSection("content"); ?>
    <accounting-identities-create-component
            :banks='<?php echo json_encode($banks, 15, 512) ?>'
            :editing-identity="true"
            :identity='<?php echo json_encode($identity, 15, 512) ?>'
            :identity-details='<?php echo json_encode($identity->details, 15, 512) ?>'
            :identity-gateways='<?php echo json_encode($identity->gateways, 15, 512) ?>'
    ></accounting-identities-create-component>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/identities/edit.blade.php ENDPATH**/ ?>