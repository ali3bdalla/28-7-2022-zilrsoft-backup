<?php $__env->startSection('title', auth()->user()->organization->title_ar ); ?>
<?php $__env->startSection('route',route('management.dashboard')); ?>




<?php $__env->startSection('content'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/home.blade.php ENDPATH**/ ?>