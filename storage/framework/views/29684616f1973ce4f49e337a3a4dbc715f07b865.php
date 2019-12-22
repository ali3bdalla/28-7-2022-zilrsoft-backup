<?php $__env->startSection('title',__('sidebar.settings')); ?>
<?php $__env->startSection('desctipion',__('pages/settings.settings')); ?>
<?php $__env->startSection('route',route('management.users.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="">
         <hr>
        <div class="columns">







            <div class="column">
                <a href="<?php echo e(route('management.settings.payment_accounts')); ?>" class="box">
                    <h1 class="title text-center">
                        الحسابات المالية للمنشأة
                    </h1>
                </a>
            </div>
            <div class="column">
                <a href="<?php echo e(route('management.expenses.index')); ?>" class="box">
                    <h1 class="title text-center">
                        <?php echo e(__('pages/settings.expenses')); ?>

                    </h1>
                </a>
            </div>
        </div>


    
    
    






















































        <div class="columns">








                













            <div class="column">
                <a href="<?php echo e(route('management.settings.global.printers')); ?>" class="box">
                    <h1 class="title text-center">
                        ادارة الطابعات
                    </h1>







                </a>
            </div>
        </div>







    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/settings/index.blade.php ENDPATH**/ ?>