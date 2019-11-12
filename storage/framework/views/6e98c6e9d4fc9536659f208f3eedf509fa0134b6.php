<?php $__env->startSection('title','الحسابات المالية | ' . $user->name); ?>
<?php $__env->startSection('desctipion',__('pages/settings.payments')); ?>
<?php $__env->startSection('route',route('management.settings.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="box">

        <div class="card-body">
            <a href="<?php echo e(route('management.users.create_payments_accounts',$user->id)); ?>" class="button is-info
            pull-right">
                <i class='fa  fa-plus-circle'></i>&nbsp;اضافة حساب مالي جديد
            </a>

            <br>
        </div>
    </div>

    <div class="">

        <div class="panel">
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="panel-body">
                    <h1 class="title">
                        <?php echo e($account[0]['gateway']['name']); ?>

                    </h1>
                </div>
                <div class="panel-footer">
                    <div class="tags are-large">
                        <?php $__currentLoopData = $account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag is-primary ">
                             <?php if(!empty($acc['bank'])): ?>
                                    : <?php echo e($acc['bank']['name']); ?>

                                <?php endif; ?>

                                <?php echo e($acc['account']); ?> &nbsp;&nbsp;
                            <?php if(!empty($acc['account_name'])): ?>

                                    : <?php echo e($acc['account_name']); ?> &nbsp;&nbsp;
                                <?php endif; ?>
                            <a href="" class="delete is-small"></a>

                        </span> &nbsp;&nbsp;&nbsp;&nbsp;
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/users/accounts.blade.php ENDPATH**/ ?>