<?php $__env->startSection('title',__('pages/categories.filters') . ' | ' .  $category->locale_name); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <accounting-category-filters-list-component
            :sys_filters='<?php echo json_encode($all_filters, 15, 512) ?>'
            :cat_filters='<?php echo json_encode($cat_filters, 15, 512) ?>'
            :can-create-filter='<?php echo e(auth()->user()->canDo('create filter')); ?>'
            :category='<?php echo json_encode($category, 15, 512) ?>'></accounting-category-filters-list-component>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/categories/filters.blade.php ENDPATH**/ ?>