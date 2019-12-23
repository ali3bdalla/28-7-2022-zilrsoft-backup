<?php $__env->startSection('title',__('pages/users.edit') . " | "  . $manager->locale_name); ?>



<?php $__env->startSection("content"); ?>
    <accounting-managers-create-component
            :editing-manager="true"
            :manager='<?php echo json_encode($manager, 15, 512) ?>'
            :manager-user='<?php echo json_encode($manager->user, 15, 512) ?>'
            :manager-department='<?php echo json_encode($manager->department, 15, 512) ?>'
            :manager-branch='<?php echo json_encode($manager->branch, 15, 512) ?>'
            :manager-permissions='<?php echo json_encode($manager->permissions, 15, 512) ?>'
            :branches='<?php echo json_encode($branches, 15, 512) ?>'>

    </accounting-managers-create-component>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/managers/edit.blade.php ENDPATH**/ ?>