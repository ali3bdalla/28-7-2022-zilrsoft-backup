<!DOCTYPE html>
<html lang="en" <?php if(app()->isLocale( 'ar')): ?>dir="rtl"<?php endif; ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('title',config('app.name')); ?></title>
    <script defer src="<?php echo e(asset('js/app_v2.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('css/tailwind.css')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php if ($__env->exists("layouts.web.styles")) echo $__env->make("layouts.web.styles", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>
<body>

<div>
    <?php if ($__env->exists('layouts.web.header')) echo $__env->make('layouts.web.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>
<?php if ($__env->exists("layouts.web.footer")) echo $__env->make("layouts.web.footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/layouts/web/master.blade.php ENDPATH**/ ?>