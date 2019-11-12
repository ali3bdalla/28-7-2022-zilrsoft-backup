<?php $__env->startSection('title',__("sidebar.purchases")); ?>
<?php $__env->startSection('route',route('management.purchases.index')); ?>


<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/invoice'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>



<?php $__env->startSection('content'); ?>
    <div class="">
        <div class="row">
            <div class="col-md-12">
            <edit-purchase-form-component
                :user='<?php echo json_encode($purchase->vendor, 15, 512) ?>'
                :creator='<?php echo json_encode($invoice->creator, 15, 512) ?>'
                :pitems='<?php echo json_encode($items, 15, 512) ?>'
                :invoice='<?php echo json_encode($invoice, 15, 512) ?>'
                :department='<?php echo json_encode($invoice->department, 15, 512) ?>'
                :purchase='<?php echo json_encode($purchase, 15, 512) ?>'>

            </edit-purchase-form-component>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/purchases/edit.blade.php ENDPATH**/ ?>