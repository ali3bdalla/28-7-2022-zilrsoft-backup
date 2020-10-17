<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $__env->yieldContent('title',config('app.name')); ?></title>
        <script src="<?php echo e(asset('js/authentication.js')); ?>" defer></script>
        <link rel="stylesheet" href="<?php echo e(asset('css/tailwind.css')); ?>">
        <link rel="stylesheet" href="<?php echo e(asset('css/rtl.css')); ?>">
    </head>
    <body>

        <div class="bg-blue-400 p-2 shadow-lg">
            <span class="text-white text-2xl"><?php echo e(config('app.name')); ?></span>
        </div>
        <div class=" pt-10 md:pt-32 text-center object-center">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </body>
</html>
<?php /**PATH /var/www/vhosts/zilrsoft.com/Modules/Authentication/Resources/views/layouts/master.blade.php ENDPATH**/ ?>