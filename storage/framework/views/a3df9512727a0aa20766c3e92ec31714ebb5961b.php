<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <title><?php echo $__env->yieldContent('title',config("app.name")); ?></title>


    <script src="https://zilrsoftproject.test/js/upload_images.js" defer></script>


    <?php echo $__env->yieldContent('sub_defer_javascript'); ?>
    <?php echo $__env->yieldContent('translator'); ?>
    <link href="https://zilrsoftproject.test/lib/css/bootstrap.min.css" rel="stylesheet" />
    
    
    
    
    
    
    
    
    <link href="https://zilrsoftproject.test/css/tailwind.css" rel="stylesheet" />

    <style type="text/css">
        * {
            direction: rtl !important;
        }
    </style>
</head>
<body class="sidebar-mini skin-blue">
<?php echo $__env->yieldContent('content'); ?>

</body>
</html>

<?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/images_uploads/layout.blade.php ENDPATH**/ ?>