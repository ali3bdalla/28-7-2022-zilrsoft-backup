<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <script defer>
        window.reusable_translator = `<?php echo json_encode(trans('reusable'), 15, 512) ?>`;
        window.messages = `<?php echo json_encode(trans('messages'), 15, 512) ?>`
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="primary-color" content="#0984e3">
    <meta name="second-color" content="#3498db">
    <meta name="app-locate" content="<?php echo e(app()->getLocale()); ?>">
    <meta name="app-name" content="<?php echo e(config('app.name')); ?>">
    <meta name="app-base-url" content="<?php echo e(route('accounting.dashboard.index')); ?>">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <meta name="" content="">
    <title><?php echo $__env->yieldContent('title',config("app.name")); ?></title>
    <script src="http://127.0.0.1:8000/js/app.js" defer></script>
    <link href="http://127.0.0.1:8000/lib/css/bootstrap.min.css" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/lib/css/AdminLTE.min.css" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/lib/css/_all-skins.min.css" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/lib/css/select2.min.cs.css" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/lib/css/buttons.css" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/lib/css/main.css" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/lib/css/bootstrap-rtl.css" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/lib/css/AdminLTE-rtl.min.css" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/css/rtl.css" rel="stylesheet" />

</head>
<body class="sidebar-mini skin-green">
<div class="wrapper" id="app">
    <?php use App\Invoice;use App\ManagerPrivateTransactions;

    $pending_transactions = ManagerPrivateTransactions::where([['is_pending', true], ['transaction_type',
        'transfer'],
        ['receiver_id', auth()->user()->id]])->with('creator',
        'receiver')->get();

    ?>


    
    
    
    
    
    
    
    


    <header class="main-header " style="    margin-bottom: 50px;">
        <a href="<?php echo e(url('/')); ?>" class="logo">
            <span class="logo-lg"><b><?php echo e(config('app.name')); ?></b></span>
        </a>
        <a class="sidebar-toggle" data-toggle="push-menu" href="#" role="button">
            <span class="sr-only">Menu</span>
            <i class="fa fa-bars"></i>

        </a>
        <nav class="navbar navbar-fixed-top" style="margin-bottom: 50px !important;">
            <div class="right" style="">
                <ul class="nav navbar-nav">

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('confirm purchase')): ?>
                        <li>
                            <a class="dropdown-toggle" href="<?php echo e(route('purchases.pending')); ?>">
                               <pending-purchases-counter-component></pending-purchases-counter-component>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="dropdown notifications-menu">
                        <a aria-expanded="true" class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-bell" style="font-size: 19px;margin-bottom: -10px;"></i>
                            
                        </a>
                        <ul class="dropdown-menu">
                            
                            
                            
                            

                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            
                            

                            
                            
                            
                        </ul>
                    </li>
                    <li class="dropdown user user-menu  dropdown-menu-right pull-right">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <img alt="User Image" class="user-image"
                                 src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg">
                            <span class="hidden-xs"></span>
                            <?php echo e(auth()->user()->locale_name); ?>

                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img alt="User Image" class="img-circle"
                                     src="https://adminlte.io/themes/AdminLTE/dist/img/user2-160x160.jpg">
                                <p>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a class="btn btn-default btn-flat" href=""><?php echo app('translator')->get('layouts.profile'); ?></a>
                                </div>
                                <div class="pull-right">
                                    <?php if ($__env->exists('layouts.buttons.logout_button')) echo $__env->make('layouts.buttons.logout_button', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li><a href="<?php echo e(route('configuration.index')); ?>"><?php echo app('translator')->get('layouts.configuration'); ?></a></li>
                    <li><a href="<?php echo e(route('configuration.hardware')); ?>"><?php echo app('translator')->get('layouts.hardware_configuration'); ?></a></li>
                    <li><a href="<?php echo e(route('statistics.index')); ?>"><?php echo app('translator')->get('layouts.statistics'); ?></a></li>
                    <li><a href="<?php echo e(route('items.index')); ?>"> <?php echo app('translator')->get('layouts.items'); ?></a></li>
                    <li><a href="<?php echo e(route('sales.create')); ?>"><?php echo app('translator')->get('layouts.create_sales'); ?></a></li>
                    <li><a href="<?php echo e(route('purchases.create')); ?>"><?php echo app('translator')->get('layouts.create_purchases'); ?></a></li>
                    <li><a href="<?php echo e(route('daily.index')); ?>"> <?php echo app('translator')->get('layouts.daily'); ?></a></li>
                </ul>
            </div>
        </nav>
    </header>


    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <?php echo $__env->yieldContent('title'); ?>
                <small><?php echo $__env->yieldContent('buttons'); ?></small>
            </h1>
        </section>
        <div class="content">
            <?php echo $__env->yieldContent("before_content"); ?>
            <?php echo $__env->yieldContent('content'); ?>
            <?php echo $__env->yieldContent("after_content"); ?>
        </div>
    </div>
    <aside class="main-sidebar" style="z-index:108;padding-top: 0px !important;">
        <?php if ($__env->exists('accounting.layout.head.sidebar')) echo $__env->make('accounting.layout.head.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </aside>

    <footer class="main-footer">
        <?php if ($__env->exists('layouts.footer')) echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </footer>
</div>

<?php echo $__env->yieldContent('page_js'); ?>
</body>
</html>
<?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/layouts/master.blade.php ENDPATH**/ ?>