<div class="banner-section spad">
    <div class="container-fluid">
        <div class="row">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <a href="<?php echo e(route('web.categories.show',$category->id)); ?>" class="col-lg-4 col-6 col-xs-6 ">
                        <div class="single-banner">


                                    <img src="<?php echo e($category->web_cover_url); ?>" alt=""  class="grid-image">






                            <div class="inner-text">
                                <h4 class="grid-title"><?php echo e($category->locale_name); ?></h4>
                            </div>
                        </div>
                    </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Web/Resources/views/categories/layout/subCategoriesCollection.blade.php ENDPATH**/ ?>