<?php $__env->startSection('title','create payment method '); ?>
<?php $__env->startSection('desctipion','create payment method '); ?>
<?php $__env->startSection('route',route('management.gateways.index')); ?>

<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/items'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="message-header is-primary">
        create new payments method
    </div>
    <div class="box">




        <form method="post" action="<?php echo e(route('management.gateways.index')); ?>">
            <?php echo csrf_field(); ?>


        </form>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/settings/ways/create.blade.php ENDPATH**/ ?>