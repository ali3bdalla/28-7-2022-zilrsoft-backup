<?php $__env->startSection('title',__('pages/accounts.creation')); ?>



<?php $__env->startSection("before_content"); ?>

<?php $__env->stopSection(); ?>




<?php $__env->startSection("content"); ?>
    <div class="panel panel-custom-form">
        <form method="post" action="<?php echo e(route('api.accounts.store')); ?>" class="panel-body">
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
                               placeholder="<?php echo e(trans('pages/categories.ar_name')); ?>" value="<?php echo e(old('ar_name')); ?>">
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
                               placeholder="<?php echo e(trans('pages/categories.name')); ?>" value="<?php echo e(old('name')); ?>">
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
                <div class="col-md-6">
                    <div class="form-group <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <select class="form-control" name="parent_id" placeholder="<?php echo e(trans('pages/categories.parent_id')); ?>">
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option
                                        <?php if(!empty(old('parent_id')) && old('parent_id')==$category['id'] && old
                                        ('parent_id')!=0): ?>
                                        selected
                                        <?php elseif($parent_id==$category['id'] && empty(old('parent_id'))): ?>
                                        selected
                                        <?php endif; ?>
                                        class="form-control" value="<?php echo e($category['id']); ?>"><?php echo e($category['locale_name']); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['parent_id'];
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
                    <div class="form-group <?php $__errorArgs = ['account_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <select class="form-control" name="account_type" placeholder="<?php echo e(trans('pages/accounts.account_type')); ?>">
                            <option
                                    <?php if(!empty(old('account_type')) && old('account_type')=='debit'): ?>
                                    selected

                                    <?php endif; ?>
                                    class="form-control" value="debit">مدين
                            </option>

                            <option
                                    <?php if(!empty(old('account_type')) && old('account_type')=='credit'): ?>
                                    selected
                                    <?php endif; ?>
                                    class="form-control" value="credit">دائن
                            </option>

                        </select>
                        <?php $__errorArgs = ['account_type'];
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

            <div class="col-md-6">
                <toggle-button

                        name="is_gateway"
                        :async="true"
                        :font-size="19" :height='30' :labels="{checked: 'خزينة', unchecked: 'حساب عادي '}"
                        :width='140'
                ></toggle-button>
            </div>

            <div class="row">
                <div class="col-md-5 col-md-offset-1">
                    <button type="submit" class="btn btn-custom-primary"><?php echo e(trans('buttons.create')); ?></button>
                </div>
                <div class="col-md-2 col-md-offset-3">

                    <a href="<?php echo e(route('accounting.accounts.index')); ?>" class="btn btn-custom-default"><?php echo e(trans
                        ('reusable.back')); ?></a>

                </div>
            </div>

        </form>
    </div>
<?php $__env->stopSection(); ?>





<?php $__env->startSection("after_content"); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('accounting.layout.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /private/var/www/workspace/zilrsoftproject/resources/views/accounting/charts/create.blade.php ENDPATH**/ ?>