<div class="breacrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php if($page == 'show-category'): ?>
                <div class="breadcrumb-text">
                    <a href="<?php echo e(route('web.index')); ?>"><i class="fa fa-home"></i> Home</a>
                    <span><?php echo e($category->web_name); ?></span>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>

</div><?php /**PATH /usr/local/var/www/vhosts/zilrsoft/Modules/Web/Resources/views/layouts/breacrumb.blade.php ENDPATH**/ ?>