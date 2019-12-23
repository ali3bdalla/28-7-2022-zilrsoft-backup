<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Scripts -->


<!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet"/>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">

    <link href="<?php echo e(asset('css/aliframework.css')); ?>" rel="stylesheet">


    <?php if(app()->isLocale('ar')): ?>
        <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="<?php echo e(asset('css/rlt/custom.css')); ?>">

        <style>
            * {
                text-align: right;
            }

            .main-sidebar {
                padding-top: 0px !important;
            }
        </style>
    <?php endif; ?>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <?php echo e(config('app.name', 'Laravel')); ?>

            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="<?php echo e(__('Toggle navigation')); ?>">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto" id='navbar-list'>
                    <!-- Authentication Links -->
                    <?php if(auth()->guard()->guest()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo e(route('accounting.login')); ?>"><?php echo e(__('Login')); ?></a>
                        </li>
                        <?php if(Route::has('accounting.register')): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?php echo e(route('accounting.register')); ?>"><?php echo e(__('Register')); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class='fa fa-bell'></i> </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class='fa fa-gear'></i> </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class='fa fa-user'></i> </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class='fa fa-bars'></i> </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <?php echo e(Auth::user()->user->name); ?> <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="<?php echo e(route('accounting.logout')); ?>"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    <?php echo e(__('Logout')); ?>

                                </a>

                                <form id="logout-form" action="<?php echo e(route('accounting.logout')); ?>" method="POST"
                                      style="display: none;">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <?php if(auth()->guard()->check()): ?>
            <?php if(Auth::user()->email_verified_at==null): ?>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card alert-danger">
                                <div class="card-body">
                                    <?php if(session('resent')): ?>
                                        <div class="alert alert-success" role="alert">
                                            <?php echo e(__('A fresh verification link has been sent to your email address.')); ?>



                                        </div>
                                    <?php endif; ?>

                                    <?php echo e(__('Before proceeding, please check your email for a verification link.')); ?>

                                    <?php echo e(__('If you did not receive the email')); ?>, <?php echo e(Auth::user()->email); ?> <a
                                            href="<?php echo e(route('accounting.verification.resend')); ?>"><?php echo e(__('click here to request another')); ?> </a>.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
            <?php endif; ?>

        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>
<script
        src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script src="<?php echo e(asset('js/default.js')); ?>"></script>
</body>
</html>
<?php /**PATH /home/vagrant/code/zilrsoft/resources/views/old/layouts/app.blade.php ENDPATH**/ ?>