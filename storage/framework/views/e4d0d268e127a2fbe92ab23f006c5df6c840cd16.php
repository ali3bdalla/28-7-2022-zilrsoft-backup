<?php $__env->startSection('title', auth()->user()->organization->title_ar ); ?>
<?php $__env->startSection('route',route('management.dashboard')); ?>




<?php $__env->startSection('content'); ?>
    <div class="box">

        <div class="card-body">
            <div class="columns">
                <div class="column"></div>
                <div class="column text-left">
                    <live-server-date-and-time></live-server-date-and-time>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <hr>
        <div class="columns">
            <div class="column">
                <div class="box">
                    <h1 class="title text-center">
                        <?php echo e(__('sidebar.sales')); ?>

                    </h1>
                    <span class="subtitle"><?php echo e(money_format('%i',auth()->user()->organization->sales->count())); ?></span>
                </div>
            </div>
            <div class="column">
                <div class="box">
                    <h1 class="title text-center">
                        <?php echo e(__('sidebar.purchases')); ?>

                    </h1>
                    <span class="subtitle"><?php echo e(money_format('%i',auth()->user()->organization->sales->count())); ?></span>
                </div>
            </div>








        </div>





    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\man-v\OneDrive\Desktop\server\htdocs\resources\views/home.blade.php ENDPATH**/ ?>