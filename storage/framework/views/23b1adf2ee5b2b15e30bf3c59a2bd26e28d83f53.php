<?php $__env->startSection('translator'); ?>
    <script defer>
        window.translator = `<?php echo json_encode(trans('pages/items'), 15, 512) ?>`
    </script>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('title',$kit->name); ?>
<?php $__env->startSection('desctipion','here you can create new items kit package'); ?>
<?php $__env->startSection('route',route('management.kits.index')); ?>



<?php $__env->startSection('content'); ?>
    <div class="">
        <div class="row">
            <div class="col-md-12">
                <create-kit-component
                        :kit='<?php echo json_encode($kit, 15, 512) ?>'
                        :creator='<?php echo json_encode(auth()->user()->with('department', 'branch')->first(), 512) ?>'
                        :is_update_mode="true"
                ></create-kit-component>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/kits/edit.blade.php ENDPATH**/ ?>