<?php $__env->startSection('title'); ?>

    <?php echo e($item->locale_name); ?> <small><?php echo e($item->barcode); ?></small>

    <?php $__env->stopSection(); ?>
<?php $__env->startSection('desctipion',$item->barcode); ?>
<?php $__env->startSection('route',route('management.items.index')); ?>



<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = `<?php echo json_encode(trans('pages/items'), 15, 512) ?>`
    </script>
<?php $__env->stopSection(); ?>








<?php $__env->startSection("page_js"); ?>
<?php $__env->stopSection(); ?>








<?php $__env->startSection("content"); ?>
    <div class="">
        <item-movement-history-component :item='<?php echo json_encode($item, 15, 512) ?>'></item-movement-history-component>
    </div>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/items/movement.blade.php ENDPATH**/ ?>