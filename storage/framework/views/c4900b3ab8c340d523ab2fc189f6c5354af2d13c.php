<?php $__env->startSection('page'); ?>
    <div class="container mx-auto h-full flex justify-center items-center">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/layouts/auth.blade.php ENDPATH**/ ?>