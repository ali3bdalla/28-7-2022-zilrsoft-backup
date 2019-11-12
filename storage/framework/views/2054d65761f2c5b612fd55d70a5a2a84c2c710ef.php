<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/categories'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('title', __('sidebar.categories')); ?>
<?php $__env->startSection('desctipion',__('sidebar.categories')); ?>
<?php $__env->startSection('route',route('management.categories.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?php echo e(route('management.accounting.chart_accounts.create')); ?>" class="button is-primary  pull-right"><i
                        class='fa fa-plus-circle'></i> اضافة حساب</a>
            <p></p>
            <br>

        </div>
        <div class="panel-body">
            <accounting-chart-accounts-list-component :categories='<?php echo json_encode($chart_accounts, 15, 512) ?>' base-url="<?php echo e(route
            ('management.accounting.chart_accounts.index')); ?>"></accounting-chart-accounts-list-component>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/accounting/chartaccounts/index.blade.php ENDPATH**/ ?>