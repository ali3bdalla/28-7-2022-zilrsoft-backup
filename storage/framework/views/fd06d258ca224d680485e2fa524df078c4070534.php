<?php $__env->startSection('title', __('sidebar.items')); ?>
<?php $__env->startSection('desctipion',__('pages/items.description')); ?>
<?php $__env->startSection('route',route('management.items.index')); ?>

<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = `<?php echo json_encode(trans('pages/items'), 15, 512) ?>`
    </script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_css'); ?>
    <style type="text/css">
        th {
            text-align: center !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    

    <div class="box">


        <div class="card-body">
            <a href="<?php echo e(route('management.items.create')); ?>" class="button is-info pull-right">
                <i class='fa  fa-plus-circle'></i>&nbsp; <?php echo e(__('pages/items.create')); ?></a>
            <span class="subtitle"></span>

            <br>
        </div>
    </div>
    <div class="card">
        <?php if(isset($_GET['selectable'])): ?>


            <?php if(isset($_GET['is_purchase'])): ?>
                <item-list-table-component
                        :linkable="1"
                        :is_ask_for_purchase="1"
                        load-type="<?php echo e($loadType); ?>"
                        :creators="<?php echo e($creators); ?>"></item-list-table-component>

            <?php else: ?>
                <item-list-table-component
                        :linkable="1"
                        :is_ask_for_purchase="0"
                        load-type="<?php echo e($loadType); ?>"
                        :creators="<?php echo e($creators); ?>"></item-list-table-component>

            <?php endif; ?>

        <?php else: ?>
            <item-list-table-component
                    :linkable="0"
                    load-type="<?php echo e($loadType); ?>"
                    :creators="<?php echo e($creators); ?>"></item-list-table-component>
        <?php endif; ?>

    </div>
    



<?php $__env->stopSection(); ?>


<?php $__env->startSection('page_js'); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\man-v\OneDrive\Desktop\server\htdocs\resources\views/items/index2.blade.php ENDPATH**/ ?>