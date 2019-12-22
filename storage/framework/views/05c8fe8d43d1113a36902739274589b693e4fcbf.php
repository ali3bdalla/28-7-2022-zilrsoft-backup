<?php $__env->startSection('title',__('pages/filters.edit') . ' | ' . $filter->locale_name); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <accounting-edit-filter-and-values-component
            :filter='<?php echo json_encode($filter, 15, 512) ?>'
    >
    </accounting-edit-filter-and-values-component>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/filters/edit.blade.php ENDPATH**/ ?>