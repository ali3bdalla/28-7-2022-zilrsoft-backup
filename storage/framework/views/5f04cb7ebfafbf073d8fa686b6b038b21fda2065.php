<?php $__env->startSection('title',__('sidebar.items')); ?>
<?php $__env->startSection('desctipion',__('pages/items.create')); ?>
<?php $__env->startSection('route',route('management.items.index')); ?>

<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/items'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <article class="message  is-info">
                    <?php if(isset($isEdited)): ?>
                        <new-item-form-component
                                :vendors='<?php echo json_encode($vendors, 15, 512) ?>'
                                :categories='<?php echo json_encode($categories, 15, 512) ?>' :is-edited="true"
                                :is-cloned="true"
                                :item="<?php echo e($item); ?>"
                                :item-filters='<?php echo json_encode($item->filters, 15, 512) ?>'
                                :item-category='<?php echo json_encode($item->category, 15, 512) ?>'></new-item-form-component>
                    <?php elseif($isClone): ?>
                        <new-item-form-component
                                :vendors='<?php echo json_encode($vendors, 15, 512) ?>'

                                :categories='<?php echo json_encode($categories, 15, 512) ?>' :is-edited="false"
                                :is-cloned="true"
                                :item="<?php echo e($item); ?>"
                                :item-filters='<?php echo json_encode($item->filters, 15, 512) ?>'
                                :item-category='<?php echo json_encode($item->category, 15, 512) ?>'></new-item-form-component>
                    <?php else: ?>
                        <new-item-form-component
                                :vendors='<?php echo json_encode($vendors, 15, 512) ?>'
                                :categories='<?php echo json_encode($categories, 15, 512) ?>'
                                :is-cloned="false"></new-item-form-component>
                    <?php endif; ?>


                </article>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/items/create2.blade.php ENDPATH**/ ?>