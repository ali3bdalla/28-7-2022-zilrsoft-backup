<?php $__env->startSection('title',__('sidebar.serial_history')); ?>
<?php $__env->startSection('desctipion',__('sidebar.serial_history')); ?>
<?php $__env->startSection('route',route('management.serial_history.index')); ?>


<?php $__env->startSection('content'); ?>

    <div class="box">
        <form method="get" rel="serial_form" action="<?php echo e(route('management.serial_history.show')); ?>">
            <div class="field">
                <input class="input" name="serial" placeholder="<?php echo e(__('pages/serial.serial')); ?>"
                @keyup.enter="this.$refs.serial_form.submit()"
                ></input>
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
            <div class="form-group">
                <button class="button is-primary" type="submit"><?php echo e(__('pages/serial.show_history')); ?></button>
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/serials/index.blade.php ENDPATH**/ ?>