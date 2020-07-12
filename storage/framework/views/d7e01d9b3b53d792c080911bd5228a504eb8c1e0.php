<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script src="<?php echo e(asset('js/app.js')); ?>" defer></script>

</head>
<body>
<div id="app">
    <test-component></test-component>
</div>
</body>
</html>
<?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/test.blade.php ENDPATH**/ ?>