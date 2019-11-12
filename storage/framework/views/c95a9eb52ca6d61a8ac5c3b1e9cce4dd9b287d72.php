<?php $__env->startSection('title',__('sidebar.beginning_inventory')); ?>
<?php $__env->startSection('desctipion',__('pages/invoice.create')); ?>
<?php $__env->startSection('route',route('management.inventories.beginning.index')); ?>


<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/invoice'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>




<?php $__env->startSection('content'); ?>
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <create-beginning-inventory-form-component
                    :user='<?php echo json_encode($user, 15, 512) ?>'
                    :creator='<?php echo json_encode(auth()->user()->with('department', 'branch')->first(), 512) ?>'
                >
                </create-beginning-inventory-form-component>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/inventories/beginning/create.blade.php ENDPATH**/ ?>