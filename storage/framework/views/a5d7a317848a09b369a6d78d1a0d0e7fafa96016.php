<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <?php if ($__env->exists("accounting.layout.head.meta")) echo $__env->make("accounting.layout.head.meta", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title><?php echo $__env->yieldContent('title',config("app.name")); ?></title>
    <?php echo $__env->yieldContent('translator'); ?>
    <?php if ($__env->exists("accounting.layout.head.defer_js")) echo $__env->make("accounting.layout.head.defer_js", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if ($__env->exists("accounting.layout.head.css")) echo $__env->make("accounting.layout.head.css", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('page_css'); ?>
</head>
<body class="sidebar-mini skin-blue sidebar-collapse">
<?php if ($__env->exists("accounting.layout.layout")) echo $__env->make("accounting.layout.layout", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php if ($__env->exists("accounting.layout.head.js")) echo $__env->make("accounting.layout.head.js", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('page_js'); ?>
</body>
</html>
<?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/layout/master.blade.php ENDPATH**/ ?>