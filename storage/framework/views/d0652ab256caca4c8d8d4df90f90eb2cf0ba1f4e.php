<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/categories'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('title', __('sidebar.chart_of_accounts')); ?>
<?php $__env->startSection('desctipion',__('sidebar.categories')); ?>
<?php $__env->startSection('route',route('management.accounts.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="<?php echo e(route('management.accounts.create')); ?>" class="button is-primary  pull-right"><i
                        class='fa fa-plus-circle'></i> اضافة حساب</a>
            <p></p>
            <br>

        </div>
        <div class="panel-body">
            <chart-of-accounts-component :accounts='<?php echo json_encode($accounts, 15, 512) ?>' base-url="<?php echo e(route('management.accounts.index')); ?>"></chart-of-accounts-component>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounts/index.blade.php ENDPATH**/ ?>