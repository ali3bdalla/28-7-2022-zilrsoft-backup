<?php $__env->startSection('content'); ?>
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: <?php echo config('accounting.name'); ?>

    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/Modules/Accounting/Resources/views/index.blade.php ENDPATH**/ ?>