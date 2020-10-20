<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <?php if ($__env->exists("accounting.layout.head.meta")) echo $__env->make("accounting.layout.head.meta", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <title><?php echo $__env->yieldContent('title',config("app.name")); ?></title>




    <script defer>
        window.reusable_translator = `<?php echo json_encode(trans('reusable'), 15, 512) ?>`;
        window.messages = `<?php echo json_encode(trans('messages'), 15, 512) ?>`
    </script>
    <script src="https://zilrsoft.app/accounting/js/rsvp.min.js" defer></script>
    <script src="https://zilrsoft.app/accounting/js/font_awesome.js" defer></script>
    <script src="https://zilrsoft.app/js/app.js" defer></script>


    <?php echo $__env->yieldContent('sub_defer_javascript'); ?>
    <?php echo $__env->yieldContent('translator'); ?>
    <link href="https://zilrsoft.app/lib/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://zilrsoft.app/lib/css/AdminLTE.min.css" rel="stylesheet" />
    <link href="https://zilrsoft.app/lib/css/_all-skins.min.css" rel="stylesheet" />

    <link href="https://zilrsoft.app/lib/css/buttons.css" rel="stylesheet" />
    <link href="https://zilrsoft.app/lib/css/main.css" rel="stylesheet" />
    <link href="https://zilrsoft.app/lib/css/bootstrap-rtl.css" rel="stylesheet" />
    <link href="https://zilrsoft.app/lib/css/AdminLTE-rtl.min.css" rel="stylesheet" />
    <link href="https://zilrsoft.app/css/rtl.css" rel="stylesheet" />
    <?php echo $__env->yieldContent('sub_styles'); ?>


    <?php echo $__env->yieldContent('page_css'); ?>

    <style type="text/css">
        input {
            direction: ltr !important;
        }
    </style>
</head>
<body class="sidebar-mini skin-blue">
    <?php if ($__env->exists("accounting.layout.layout")) echo $__env->make("accounting.layout.layout", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php if ($__env->exists("accounting.layout.head.js")) echo $__env->make("accounting.layout.head.js", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('sub_javascript'); ?>
    <?php echo $__env->yieldContent('page_js'); ?>
</body>
</html>
<?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/layout/master.blade.php ENDPATH**/ ?>