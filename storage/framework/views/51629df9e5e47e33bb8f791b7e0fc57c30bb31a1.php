<?php if(!$isClone): ?>
    <?php $__env->startSection('title',__('pages/items.edit') . ' | ' .  $item->locale_name); ?>
<?php else: ?>
    <?php $__env->startSection('title',__('pages/items.clone') . ' | ' .  $item->locale_name); ?>
<?php endif; ?>


<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>

    <accounting-items-create-component
            :editing-item="true"
            :cloning-item="<?php echo e($isClone); ?>"
            :edited-item-data="<?php echo e($item); ?>"
            :edited-item-filters='<?php echo json_encode($item->filters, 15, 512) ?>'
            :edited-item-category='<?php echo json_encode($item->category, 15, 512) ?>'
            :can-create-category="<?php echo e(auth()->user()->canDo('create category')); ?>"
            :can-create-filter="<?php echo e(auth()->user()->canDo('create filter')); ?>"
            :can-edit-filter="<?php echo e(auth()->user()->canDo('edit filter')); ?>"
            :vendors='<?php echo json_encode($vendors, 15, 512) ?>'
            :categories='<?php echo json_encode($categories, 15, 512) ?>'
            :is-cloned="false"
    >
    </accounting-items-create-component>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/items/edit.blade.php ENDPATH**/ ?>