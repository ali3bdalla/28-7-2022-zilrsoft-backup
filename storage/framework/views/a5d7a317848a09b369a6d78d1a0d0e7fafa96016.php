<?php $__env->startSection('layout'); ?>
    <script defer>
        window.reusable_translator = `<?php echo json_encode(trans('reusable'), 15, 512) ?>`;
        window.messages = `<?php echo json_encode(trans('messages'), 15, 512) ?>`
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.5/css/bulma.min.css">

    <link rel="stylesheet"
          href="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/dist/css/skins/_all-skins.min.css">


    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>
    
    <link rel="stylesheet" href="<?php echo e(asset('css/main.css')); ?>">
    <style>

        i {
            margin: 7px !important
        }

        .option-buttons .button {
            margin: 5px;

        }

        .dropdown-menu {
            position: absolute !important;
            will-change: transform !important;
            top: 0px !important;
            left: 0px !important;
            transform: translate3d(-58px, 41px, 0px) !important;
            /*background: #01d1b2 !important; */
            color: white;
            z-index: 10000
        }

        a.dropdown-item, button.dropdown-item {
            padding: 10px;
            /* padding-right: 3rem; */
            text-align: center;
            /* white-space: nowrap; */
            width: 100%;
            /* padding: 6px; */
            border-bottom: 1px solid #eee;
            /*color: white*/
        }

        .table-container {
            overflow: inherit;
        }


        th {
            text-align: center !important;
        }

        input {
            text-align: center !important;
        }


        button > svg {
            margin: 4px !important;
        }

        .datedirection {
            direction: ltr !important;
        }
    </style>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

    <?php if(app()->isLocale('ar')): ?>
        <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-rtl/3.4.0/css/bootstrap-rtl.css">
        <link rel="stylesheet" href="<?php echo e(asset('css/rlt/custom.css')); ?>">

        <?php endif; ?>


        <?php echo $__env->yieldContent('page_css'); ?>

        </head>
        <body class="sidebar-mini skin-green sidebar-collapse" @keydown.esc="console.log('23')">
        
        <div class="wrapper" id="app">

            <header class="main-header ">
                <?php if ($__env->exists('layouts.header')) echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </header>


            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        <?php echo $__env->yieldContent('title'); ?>
                        <small><?php echo $__env->yieldContent('description'); ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo e(route('management.dashboard')); ?>">لوحة التحكم</a></li>
                        
                    </ol>
                </section>
                <div class="content">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>

            <aside class="main-sidebar" style="z-index:108;padding-top: 0px !important;">
                <?php if ($__env->exists('layouts.sidebar')) echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </aside>
            <footer class="main-footer">
                <?php if ($__env->exists('layouts.footer')) echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


            </footer>

        </div>


        <script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="https://adminlte.io/themes/AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="https://adminlte.io/themes/AdminLTE/dist/js/adminlte.min.js"></script>


        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js" defer></script>
        
        <script src="<?php echo e(asset('js/main.js')); ?>" defer></script>
        <style scoped>
            .select2-container {
                width: 100% !important;
            }
        </style>
        <?php echo $__env->yieldContent('page_js'); ?>

        </body>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.foundation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/layout/master.blade.php ENDPATH**/ ?>