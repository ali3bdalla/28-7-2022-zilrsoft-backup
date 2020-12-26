<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Muli:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<!-- Css Styles -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/bootstrap.min.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/font-awesome.min.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/themify-icons.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/elegant-icons.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/owl.carousel.min.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/nice-select.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/jquery-ui.min.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/slicknav.min.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('Web/template/css/style.css')); ?>" type="text/css">
<link rel="stylesheet" href="<?php echo e(asset('css/web.css')); ?>">

<link rel="stylesheet" href="<?php echo e(asset('css/store.css')); ?>">
<?php if(app()->isLocale( 'ar')): ?>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@600&family=Tajawal:wght@300;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@laylazi/bootstrap-rtl@4.5.3-1/dist/css/bootstrap-rtl.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/ar/store.css')); ?>">
<?php endif; ?><?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/layouts/web/styles.blade.php ENDPATH**/ ?>