<?php $__env->startSection('title',$category->name  . ' - ' .__('pages/categories.filters')); ?>
<?php $__env->startSection('desctipion',''); ?>
<?php $__env->startSection('route',route('management.categories.filters',$category->id)); ?>



<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/categories'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
    <div class="box">
        <category-filters-component :sys_filters='<?php echo json_encode($all_filters, 15, 512) ?>' :cat_filters='<?php echo json_encode($cat_filters, 15, 512) ?>'
                                    :category='<?php echo json_encode($category, 15, 512) ?>'></category-filters-component>
    </div>

<?php $__env->stopSection(); ?>











<?php $__env->startSection("page_css"); ?>

<?php $__env->stopSection(); ?>








<?php $__env->startSection('page_js'); ?>

<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.master2',['vuejs'=>true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/categories/update_filters.blade.php ENDPATH**/ ?>