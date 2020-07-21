<?php $__env->startSection('content'); ?>
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: <?php echo config('purchases.name'); ?>

    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('purchases::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Purchases/Resources/views/index.blade.php ENDPATH**/ ?>