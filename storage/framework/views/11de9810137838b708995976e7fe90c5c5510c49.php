<?php $__env->startSection('title','users'); ?>
<?php $__env->startSection('desctipion','create user'); ?>
<?php $__env->startSection('route',route('management.users.index')); ?>

<?php $__env->startSection('content'); ?>


<div class="box">
    <create-user-form-component :branchs='<?php echo json_encode($branchs, 15, 512) ?>':banks='<?php echo json_encode($banks, 15, 512) ?>'  :gateways='<?php echo json_encode($gateways, 15, 512) ?>
            '></create-user-form-component>
</div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/learning/Desktop/code/zilrsoft/resources/views/users/create.blade.php ENDPATH**/ ?>