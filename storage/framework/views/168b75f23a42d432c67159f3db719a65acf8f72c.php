<?php $__env->startSection('title',$filter->locale_name); ?>
<?php $__env->startSection('desctipion',''); ?>
<?php $__env->startSection('route',route('management.filters.index')); ?>


<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/filters'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection("content"); ?>


    <div class="box">

        <filter-values-list-component :obj_filter='<?php echo json_encode($filter, 15, 512) ?>'
                                      :values_list='<?php echo json_encode($filter->values()->with ('creator')->get(), 15, 512) ?>'>
        </filter-values-list-component>
    </div>





<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.master2", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/filters/edit.blade.php ENDPATH**/ ?>