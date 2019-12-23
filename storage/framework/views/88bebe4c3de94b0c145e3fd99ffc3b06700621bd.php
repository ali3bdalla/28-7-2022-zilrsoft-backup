<?php $__env->startSection('title',__('pages/branches.create')); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <div class="panel panel-custom-form">
        <form method="post" action="<?php echo e(route('accounting.branches.store')); ?>" class="panel-body">
            <?php echo csrf_field(); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group <?php $__errorArgs = ['ar_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> ">
                        <input class="form-control arabic-input" name="ar_name"
                               placeholder="<?php echo e(trans('pages/branches.ar_name')); ?>" value="<?php echo e(old('ar_name')); ?>">
                        <?php $__errorArgs = ['ar_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger">
                            <?php echo e($message); ?>

                        </small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <input class="form-control arabic-input" name="name"
                               placeholder="<?php echo e(trans('pages/branches.name')); ?>" value="<?php echo e(old('name')); ?>">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger">
                            <?php echo e($message); ?>

                        </small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <input class="form-control arabic-input" name="phone_number"
                               placeholder="<?php echo e(trans('pages/branches.phone_number')); ?>" value="<?php echo e(old('phone_number')); ?>">
                        <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small class="text-danger">
                            <?php echo e($message); ?>

                        </small>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <button type="submit" class="btn btn-custom-primary"><?php echo e(trans('pages/branches.create')); ?></button>
                </div>
                <div class="col-md-2 col-md-offset-3">

                    <a href="<?php echo e(route('accounting.branches.index')); ?>"
                       class="btn btn-custom-default"><?php echo e(__('reusable.cancel')); ?></a>

                </div>
            </div>
    </div>
    </form>
    </div>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/vagrant/code/zilrsoft/resources/views/accounting/branches/create.blade.php ENDPATH**/ ?>