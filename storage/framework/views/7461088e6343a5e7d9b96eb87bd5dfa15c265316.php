<?php $__env->startSection('content'); ?>
    <?php if ($__env->exists('web::items.layout.sliderCollections')) echo $__env->make('web::items.layout.sliderCollections', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if ($__env->exists('web::categories.layout.gridCollection')) echo $__env->make('web::categories.layout.gridCollection', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('web::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Web/Resources/views/index.blade.php ENDPATH**/ ?>