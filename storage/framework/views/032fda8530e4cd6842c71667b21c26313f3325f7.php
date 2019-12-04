<?php $__env->startSection('title',__('sidebar.financial_statements')); ?>
<?php $__env->startSection('route',route('management.financial_statements.index')); ?>


<?php $__env->startSection('content'); ?>
<div class="box">
    <div class="panel">
        <div class="card" style="width: 18rem;">
            <div class="panel-body text-center">

                <a href="<?php echo e(route('management.financial_statements.trail_balance')); ?>" class="btn btn-primary">ميزان المراجعة</a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/learning/Desktop/code/zilrsoft/resources/views/financial_statements/index.blade.php ENDPATH**/ ?>