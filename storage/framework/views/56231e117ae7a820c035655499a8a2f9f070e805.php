<?php $__env->startSection('title',__('sidebar.serial_history')); ?>


<?php $__env->startSection('content'); ?>

    <div class="panel panel-custom-form">
        <div class="panel-heading">
            <form method="get" rel="serial_form" action="<?php echo e(route('accounting.items.show_serial_activities')); ?>">
                <div class="form-group">
                    <input class="form-control" name="serial"  value="<?php echo e(old('serial')); ?>" placeholder="<?php echo e(__('pages/serial.serial')); ?>"
                           @keyup.enter="this.$refs.serial_form.submit()" />
                </div>
                <?php $__errorArgs = ['serial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="help is-danger"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="form-group text-center">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <button class="btn btn-custom-primary btn-block" type="submit"><?php echo e(__('pages/serial.show_history')); ?></button>
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/accounting/items/serial/index.blade.php ENDPATH**/ ?>