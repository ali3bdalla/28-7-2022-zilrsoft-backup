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
    </head>

        <body>
            <div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div>
        </body>
        
</html>
<?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/app.blade.php ENDPATH**/ ?>