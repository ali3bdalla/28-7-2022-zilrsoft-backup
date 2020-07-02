<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Module Web</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php if ($__env->exists("web::layouts.styles")) echo $__env->make("web::layouts.styles", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </head>
    <body>


        <?php if ($__env->exists('web::layouts.header')) echo $__env->make('web::layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="container-fluid">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <?php if ($__env->exists("web::layouts.footer")) echo $__env->make("web::layouts.footer", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </body>
</html>
<?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Web/Resources/views/layouts/master.blade.php ENDPATH**/ ?>