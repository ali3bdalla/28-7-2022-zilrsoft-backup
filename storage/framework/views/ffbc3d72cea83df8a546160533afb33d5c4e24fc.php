<?php $__env->startSection('title',__('sidebar.sales')); ?>
<?php $__env->startSection('desctipion',__('pages/invoice.create')); ?>
<?php $__env->startSection('route',route('management.sales.index')); ?>

<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/invoice'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <create-quotation-form-component :salesmen='<?php echo json_encode($salesmen, 15, 512) ?>'
                                            :creator='<?php echo json_encode(auth()->user()->with
                ('department', 'branch')->first(), 512) ?>' :clients='<?php echo json_encode($clients, 15, 512) ?>'></create-quotation-form-component>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/sales/quotation_create.blade.php ENDPATH**/ ?>