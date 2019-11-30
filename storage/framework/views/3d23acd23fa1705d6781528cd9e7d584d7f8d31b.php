<?php $__env->startSection('title',__('pages/invoice.purchase')); ?>
<?php $__env->startSection('desctipion','create purchase invoice'); ?>
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

                <create-purchase-form-component2
                        :expenses='<?php echo json_encode($expenses, 15, 512) ?>'
                        :vendors='<?php echo json_encode($vendors, 15, 512) ?>'
                        :gateways='<?php echo json_encode($gateways, 15, 512) ?>'
                        :receivers='<?php echo json_encode($receivers, 15, 512) ?>'
                        :creator='<?php echo json_encode(auth()->user()->with('department', 'branch')->first(), 512) ?>'>
                </create-purchase-form-component2>

                
                
                
                
                
                

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/purchases/create.blade.php ENDPATH**/ ?>