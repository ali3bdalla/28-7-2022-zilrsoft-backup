<?php $__env->startSection('title',__('pages/items.create_kit')); ?>
<?php $__env->startSection('desctipion','here you can create new items kit package'); ?>
<?php $__env->startSection('route',route('accounting.kits.index')); ?>


<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = '<?php echo json_encode(trans('pages/items'), 15, 512) ?>'
    </script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <accounting-kits-create-component
                        :creator='<?php echo json_encode(auth()->user()->load('department', 'branch'), 512) ?>
                                '></accounting-kits-create-component>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/kits/create.blade.php ENDPATH**/ ?>