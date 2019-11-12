<?php $__env->startSection('title','payments methods'); ?>
<?php $__env->startSection('desctipion','create purchase invoice'); ?>
<?php $__env->startSection('route',route('management.sales.index')); ?>


<?php $__env->startSection('content'); ?>
    <div class="box text-right">

        <a class="button is-info" href="<?php echo e(route('management.gateways.create')); ?>"><i class="fa
        fa-plus-circle"></i>&nbsp;اضافة وسيلة دفع اخرى</a>

    </div>
    <?php if(empty(!$payments_methods)): ?>
        <?php $__currentLoopData = $payments_methods->chunk(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $methods): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="columns">
                <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="column">
                        <div class="box text-center">
                            <p class="title"><?php echo e($method->name); ?></p>
                            <p class="subtitle"><?php echo e($method->ar_name); ?></p>
                            <p class="subtitle"><?php echo e($method->created_at); ?></p>
                            <div class="box-footer">
                                <delete-button-component href="<?php echo e(route('management.gateways.remove',$method->id)); ?>"
                                                         class='button is-danger'>
                                    <i class="fa fa-trash"></i>
                                    &nbsp;delete
                                </delete-button-component>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/settings/ways/index.blade.php ENDPATH**/ ?>