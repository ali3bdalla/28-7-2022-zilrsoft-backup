<!DOCTYPE html>
<html lang="en">
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
    <div class="">
        <div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div>
    </div>
</div>


</body>
</html>

<?php /**PATH /var/www/resources/views/images_upload.blade.php ENDPATH**/ ?>