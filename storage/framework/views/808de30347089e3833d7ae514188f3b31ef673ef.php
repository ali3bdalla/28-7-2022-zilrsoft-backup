<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent("title",config("app.name")); ?></title>

    <script defer>
        window.config = `<?php echo json_encode($organization_config, 15, 512) ?>`

    </script>

    <script defer>
        window.reusable_translator = `<?php echo json_encode(trans('reusable'), 15, 512) ?>`;
        window.messages = `<?php echo json_encode(trans('messages'), 15, 512) ?>`
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php echo $__env->yieldContent('foundation_css'); ?>
</head>
<body id="page-top">
<div id="app">
<?php echo $__env->yieldContent('layout'); ?>
</div>
<?php echo $__env->yieldContent('foundation_js'); ?>
</body>
</html><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/layout/foundation.blade.php ENDPATH**/ ?>