<?php $__env->startSection('content'); ?>
    <div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.web.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/web.blade.php ENDPATH**/ ?>