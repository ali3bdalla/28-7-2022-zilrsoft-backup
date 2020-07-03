<?php $__env->startSection('content'); ?>

        <?php echo $__env->make('web::layouts.breacrumb',['page' => 'show-category'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php if($category->children()->count() >= 1): ?>
            <?php echo $__env->make('web::categories.layout.subCategoriesCollection', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('web::items.layout.verticalScrollableCollection', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php else: ?>
            <grid-items-collection-component :category-id="<?php echo e($category->id); ?>">
            </grid-items-collection-component>
        <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('web::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Web/Resources/views/categories/show.blade.php ENDPATH**/ ?>