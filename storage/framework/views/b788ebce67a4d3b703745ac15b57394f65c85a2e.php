<?php $__env->startSection('title'); ?>
    المنتجات
<?php $__env->stopSection(); ?>
<?php $__env->startSection("content"); ?>
    <h1><?php echo e($completedProducts); ?></h1>
    <div class="text-center container" id="app">
        <products-list-for-uploading-images-component query-active-model="<?php echo e($activeModel); ?>" :completed-products="<?php echo e($completedProducts); ?>" :products='<?php echo json_encode($items, 15, 512) ?>'  :items-count="<?php echo e($itemsCount); ?>" :category-id='<?php echo json_encode($categoryId, 15, 512) ?>' :categories='<?php echo json_encode($categories, 15, 512) ?>'></products-list-for-uploading-images-component>
        <?php echo $links; ?>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('images_uploads.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/images_uploads/index.blade.php ENDPATH**/ ?>