<?php $__env->startSection('title',__('sidebar.categories')); ?>
<?php $__env->startSection('buttons'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check("create category")): ?>
        <a href="<?php echo e(route('accounting.categories.create')); ?>" class="btn btn-custom-primary">
            <i class="fa fa-plus-circle"></i> <?php echo e(__('pages/categories.create')); ?>

        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <accounting-treeview-raw-layout-component
                :can-create="<?php echo e(auth()->user()->canDo('create category')); ?>"
                :can-edit="<?php echo e(auth()->user()->canDo('edit category')); ?>"
                :can-delete="<?php echo e(auth()->user()->canDo('delete category')); ?>"
                :can-view="<?php echo e(auth()->user()->canDo('view category')); ?>"
                :item='<?php echo json_encode($category, 15, 512) ?>'
                :key="<?php echo e($category->id); ?>">
        </accounting-treeview-raw-layout-component>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/resources/views/accounting/categories/index.blade.php ENDPATH**/ ?>