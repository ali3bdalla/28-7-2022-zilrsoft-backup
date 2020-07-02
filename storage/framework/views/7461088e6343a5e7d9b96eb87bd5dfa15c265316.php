<?php $__env->startSection('content'); ?>
    <div class="banner-section spad">
        <div class="container-fluid">
            <div class="row">
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('web.categories.show',$category->id)); ?>" class="col-lg-4">
                    <div class="single-banner">
                        <img src="<?php echo e($category->cover); ?>" alt=""  class="grid-image">
                        <div class="inner-text">
                            <h4 class="grid-title"><?php echo e($category->name); ?></h4>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('web::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Web/Resources/views/index.blade.php ENDPATH**/ ?>