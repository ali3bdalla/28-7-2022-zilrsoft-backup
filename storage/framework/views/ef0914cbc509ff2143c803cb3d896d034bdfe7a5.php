<?php $__env->startSection('content'); ?>
    <div class=" p-0 md:p-10 mx-2 md:mx-64 md:px-32">
        <div class="">
            <div class="">
                <h3 class="text-3xl mb-10">Log In</h3>
            </div>
            <div class=" ">
                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="">
                        <div class="">
                            <span class=""><i class="fas fa-user"></i></span>
                        </div>
                        <input type="email" class=" rounded-lg w-full px-6 py-2 text-2xl
                                shadow-lg "
                               name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus>

                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>
                    <div class="">
                        <div class="">
                            <span class=""><i class=""></i></span>
                        </div>
                        <input id="password" type="password" style="direction: ltr"
                               class="rounded-lg w-full px-6 py-2 text-2xl
                                shadow-lg mt-2" name="password" required
                               autocomplete="current-password">

                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Sign In"
                               class="bg-blue-300
                               text-xl
                               shadow-lg
                               object-center mt-3 w-full p-3 rounded-lg hover:bg-blue-500
                               text-white">
                    </div>
                </form>


            </div>
            
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('authentication::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/workspace/zilrsoft/resources/views/auth/login.blade.php ENDPATH**/ ?>