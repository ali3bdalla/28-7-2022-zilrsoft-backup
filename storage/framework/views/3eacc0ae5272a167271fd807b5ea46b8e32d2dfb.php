<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="<?php echo e(route('management.users.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="card">
                    <div class="card-header"><?php echo e(trans('users.update_user',['user'=>$user->name])); ?></div>
                    <div class="card-body">
                        <div class="form-group row text-center">
                          <!--   <label for="email" class="col-md-4 col-form-label text-md-right"><?php echo e(trans('users.email_address')); ?></label> -->
                            <div class="col-md-3">
                                <input 
                                <?php if($user->is_client): ?>
                                    checked="checked" 
                                <?php endif; ?>
                                name='is_client'
                                type="checkbox"  data-toggle="toggle" data-on="<?php echo e(trans('users.user_type',['type'=>'client,'])); ?>" data-off="<?php echo e(trans('users.user_type',['type'=>'client,'])); ?>" data-onstyle="success" data-offstyle="danger">

                            </div>


                            <div class="col-md-3">
                                <input 
                                value="true" 
                                <?php if($user->is_manager): ?>
                                    checked="checked" 
                                <?php endif; ?>
                                name='is_manager'
                                type="checkbox"  data-toggle="toggle" data-on="<?php echo e(trans('users.user_type',['type'=>'manager,'])); ?>" data-off="<?php echo e(trans('users.user_type',['type'=>'manager,'])); ?>" data-onstyle="success" data-offstyle="danger">

                            </div>
                            <div class="col-md-3">
                                <input 
                                <?php if($user->is_vendor): ?>
                                    checked="checked" 
                                <?php endif; ?>
                                name='is_vendor'
                                type="checkbox"  data-toggle="toggle" data-on="<?php echo e(trans('users.user_type',['type'=>'vendor,'])); ?>" data-off="<?php echo e(trans('users.user_type',['type'=>'vendor,'])); ?>" data-onstyle="success" data-offstyle="danger">

                            </div>

                            <div class="col-md-3">
                                <input 
                                value="true" 
                                <?php if($user->is_supplier): ?>
                                    checked="checked" 
                                <?php endif; ?>
                                name='is_supplier'
                                type="checkbox"  data-toggle="toggle" data-on="<?php echo e(trans('users.user_type',['type'=>'supplier,'])); ?>" data-off="<?php echo e(trans('users.user_type',['type'=>'supplier,'])); ?>" data-onstyle="success" data-offstyle="danger">

                            </div>
                        </div>
                        <hr/>


                        <div class="form-group row text-center">
                            <label for="memebership_type" class="col-md-3 col-form-label text-md-right"><?php echo e(trans('users.memebership_type')); ?></label>
                            
                            <div class="col-md-6">
                                <input 
                                checked
                                value="individual" 
                                name='memebership_type'
                                class="btn btn-block" 
                                type="checkbox"  data-toggle="toggle" data-on="<?php echo e(trans('users.user_type',['type'=>'individual'])); ?>" data-off="<?php echo e(trans('users.user_type',['type'=>'company,'])); ?>" data-onstyle="info" data-offstyle="warning">

                            </div>

                        </div>

                        <hr/>

                        <div class="form-group row">
                          <!--   <label for="email" class="col-md-4 col-form-label text-md-right"><?php echo e(trans('users.email_address')); ?></label> -->
                            <div class="col-md-6">
                                <input id="name" type="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name" value="<?php if(empty(old('name'))): ?><?php echo e($user->name); ?><?php else: ?> <?php echo e(old('name')); ?> <?php endif; ?>" placeholder="<?php echo e(trans('users.name')); ?>" required autocomplete="name" autofocus>

                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-md-6">
                                <input id="phone_number" type="text" class="form-control <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="phone_number" value="<?php if(empty(old('phone_number'))): ?><?php echo e($user->phone_number); ?><?php else: ?> <?php echo e(old('phone_number')); ?> <?php endif; ?>" required  placeholder="<?php echo e(trans('users.phone_number')); ?>" autofocus>

                                <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>


                        <div class="form-group row mb-0" id='user_only_submit_btn'>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <?php echo e(__('buttons.save')); ?>

                                </button>
                            </div>
                        </div> 
                    </div>
                </div>
                <hr/>
                
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /usr/local/var/www/resources/views/users/edit.blade.php ENDPATH**/ ?>