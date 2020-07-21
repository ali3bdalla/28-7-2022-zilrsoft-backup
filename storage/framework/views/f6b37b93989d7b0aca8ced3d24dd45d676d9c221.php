<?php $__env->startSection('sub_defer_javascript'); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('sub_styles'); ?>
    <link href="http://127.0.0.1:8000/modules/css/sales.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>



<?php $__env->startSection('sub_javascript'); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Sales/Resources/views/layouts/master.blade.php ENDPATH**/ ?>