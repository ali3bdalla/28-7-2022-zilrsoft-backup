<div class="banner-section spad  main-categories">
    <div class="container-fluid">
        <div class="row">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <a href="<?php echo e(route('web.categories.show',$category->id)); ?>" class="col-lg-4  col-cell">
                    <div class="card sub-category-cell">
                        <div class="grid-image">
                            <img src="<?php echo e($category->web_cover_url); ?>" alt=""  class="grid-image card-img">

                        </div>
                        <div class="card-body">
                            <span> <?php echo e($category->locale_name); ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Web/Resources/views/categories/layout/gridCollection.blade.php ENDPATH**/ ?>